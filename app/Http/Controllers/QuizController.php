<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Question;
use App\Models\Quiz\Choice;
use App\Models\Quiz\QuestionType;
use App\Models\Quiz\Subject; // <-- Impor Subject
use App\Models\Quiz\Topic;   // <-- Impor Topic
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // <-- PENAMBAHAN BARU #1: Impor facade Auth

class QuizController extends Controller
{
    /**
     * Menampilkan halaman utama editor kuis.
     * Mengambil semua subject untuk ditampilkan di modal.
     */
    public function editor(Request $request)
    {
        // Jika ini mode edit (ada parameter topic_id)
        if ($request->has('topic_id')) {
            $topic = Topic::findOrFail($request->query('topic_id'));
            return $this->edit($topic);
        }

        // Jika ini mode buat baru
        $subjects = Subject::orderBy('subject_name')->get();
        return view('quiz.editor', [
            'subjects' => $subjects,
            'topic' => null, // Tidak ada topik saat membuat baru
            'existingQuestions' => null,
        ]);
    }

    /**
     * Menyiapkan data untuk mengedit kuis yang sudah ada.
     */
    public function edit(Topic $topic)
    {
        // Pastikan pengguna hanya bisa mengedit kuis miliknya sendiri
        if ($topic->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil semua data terkait (questions, choices, questionType)
        $topic->load('questions.choices', 'questions.questionType');
        $subjects = Subject::orderBy('subject_name')->get();

        // Ubah data dari database menjadi format yang sama dengan sessionStorage
        $formattedQuestions = $topic->questions->map(function ($question) {
            $choices = $question->choices->pluck('choice_text')->toArray();
            // Pastikan selalu ada 4 elemen pilihan
            $choices = array_pad($choices, 4, '');

            $data = [
                'q_type_name' => $question->questionType->type_name,
                'question_text' => $question->question_text,
                'choices' => $choices,
            ];

            // Tambahkan jawaban yang benar sesuai tipe soal
            if ($data['q_type_name'] === 'Button') {
                $data['correct_choice'] = $question->choices->search(fn($choice) => $choice->is_correct) ?: -1;
            } elseif ($data['q_type_name'] === 'Checkbox') {
                $data['correct_choices'] = $question->choices->filter(fn($choice) => $choice->is_correct)->keys()->toArray();
            } elseif ($data['q_type_name'] === 'Reorder') {
                $data['correct_order'] = $question->choices->pluck('correct_order')->toArray();
            } elseif ($data['q_type_name'] === 'TextAnswer') {
                $data['correct_answer'] = $question->choices->first()->choice_text ?? '';
            }

            return $data;
        });

        return view('quiz.editor', [
            'topic' => $topic,
            'subjects' => $subjects,
            'existingQuestions' => $formattedQuestions->toJson() // Kirim sebagai JSON
        ]);
    }


    /**
     * Menyimpan topik baru dan semua pertanyaan dari quiz editor.
     */
    public function storeAll(Request $request)
    {
        // Aturan validasi baru
        $validatedData = $request->validate([
            'subject_id' => 'required|exists:subjects,subject_id',
            'new_topic_name' => 'required|string|max:255|min:3',
            'questions_data' => 'required|json'
        ]);

        $questionsData = json_decode($validatedData['questions_data'], true);
        
        if (empty($questionsData)) {
            return redirect()->back()->withErrors(['main' => 'No valid questions were provided.']);
        }

        $questionTypes = QuestionType::pluck('q_type_id', 'type_name');

        DB::beginTransaction();
        try {
            // 1. Buat Topik baru terlebih dahulu
            $newTopic = Topic::create([
                'topic_name' => $validatedData['new_topic_name'],
                'subject_id' => $validatedData['subject_id'],
                'user_id' => Auth::id(), // <-- PENAMBAHAN BARU #2: Tambahkan ID pengguna yang sedang login

            ]);

            // 2. Dapatkan ID dari topik yang baru dibuat
            $topicId = $newTopic->topic_id;

            // 3. Loop dan simpan setiap pertanyaan dengan topic_id yang baru
            foreach ($questionsData as $index => $data) {
                // ... (Logika validasi dan penyimpanan per soal tetap sama seperti sebelumnya)
                // Pastikan Anda menggunakan $topicId di sini
                $question = Question::create([
                    'question_text' => $data['question_text'],
                    'topic_id' => $topicId, // <-- Gunakan ID topik baru
                    'q_type_id' => $questionTypes[$data['q_type_name']] ?? null,
                ]);

                // Logika kondisional untuk menyimpan jawaban berdasarkan tipe soal
                if ($data['q_type_name'] === 'Button') {
                    if (!isset($data['correct_choice']) || $data['correct_choice'] < 0) {
                        throw new \Exception("A correct answer must be selected for question #" . ($index + 1));
                    }
                    $correctIndex = $data['correct_choice'];
                    foreach ($data['choices'] as $choiceIndex => $choiceText) {
                        Choice::create([
                            'question_id' => $question->question_id,
                            'choice_text' => $choiceText,
                            'is_correct' => ($choiceIndex == $correctIndex),
                        ]);
                    }
                } elseif ($data['q_type_name'] === 'Checkbox') {
                    if (!isset($data['correct_choices']) || !is_array($data['correct_choices']) || empty($data['correct_choices'])) {
                        throw new \Exception("At least one correct answer must be selected for question #" . ($index + 1));
                    }
                    $correctIndices = $data['correct_choices'];
                    foreach ($data['choices'] as $choiceIndex => $choiceText) {
                        Choice::create([
                            'question_id' => $question->question_id,
                            'choice_text' => $choiceText,
                            'is_correct' => in_array($choiceIndex, $correctIndices),
                        ]);
                    }
                } elseif ($data['q_type_name'] === 'TypeAnswer') {
                    // Untuk "TypeAnswer", kita simpan jawaban sebagai satu-satunya pilihan yang benar.
                    Choice::create([
                        'question_id' => $question->question_id,
                        'choice_text' => $data['choices'][0], // Ambil jawaban dari elemen pertama
                        'is_correct' => true,
                    ]);
                } elseif ($data['q_type_name'] === 'Reorder') {
                    // Validasi bahwa semua item sudah diberi peringkat
                    if (!isset($data['correct_order']) || count(array_filter($data['correct_order'], fn($v) => $v != -1)) !== 4) {
                        throw new \Exception("All items must be ranked for reorder question #" . ($index + 1));
                    }
                    $correctOrder = $data['correct_order'];
                    foreach ($data['choices'] as $choiceIndex => $choiceText) {
                        Choice::create([
                            'question_id' => $question->question_id,
                            'choice_text' => $choiceText,
                            'is_correct' => false, // Selalu false untuk tipe reorder
                            'correct_order' => $correctOrder[$choiceIndex] ?? null, // Simpan urutan yang benar
                        ]);
                    }
                }
            }

            DB::commit();
            // Arahkan ke homepage atau halaman lain dengan pesan sukses
            return redirect()->route('quiz.editor')->with('success', 'Quiz "' . $newTopic->topic_name . '" has been created successfully!');


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to save all questions: " . $e->getMessage());
            return redirect()->back()->withErrors(['main' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    // app/Http/Controllers/QuizController.php

// ... (kode controller Anda yang lain)

    /**
     * Menampilkan halaman pengerjaan kuis untuk topik tertentu.
     */
    public function start(Topic $topic)
    {
        // 1. Ambil semua pertanyaan beserta pilihan jawabannya untuk topik ini.
        //    Menggunakan load() (Eager Loading) jauh lebih efisien daripada query berulang.
        $topic->load('questions.choices');

        // 2. Format data agar sesuai dengan yang diharapkan oleh Alpine.js di view.
        //    Struktur ini harus cocok dengan yang digunakan di `quizTaker(quizData)`.
        $quizData = [
            'topic_id' => $topic->topic_id,
            'topic_name' => $topic->topic_name,
            'questions' => $topic->questions->map(function ($question) {
                return [
                    'id' => $question->question_id,
                    'question_text' => $question->question_text,
                    'q_type_name' => $question->questionType->type_name, // Jika Anda butuh tipe soal di frontend
                    'choices' => $question->choices->map(function ($choice) {
                        return [
                            'id' => $choice->choice_id,
                            'choice_text' => $choice->choice_text,
                        ];
                    })->all(), // ->all() untuk mengubah koleksi menjadi array biasa
                ];
            })->all(),
        ];

        // 3. Render view 'quiz.show' dan kirimkan data topik serta data kuis yang sudah diformat.
        //    Pastikan file view Anda bernama `show.blade.php` di dalam folder `resources/views/quiz/`
        return view('quiz.show', [
            'topic' => $topic,
            'quizData' => $quizData
        ]);
    }

    public function addbutton()
    {
        $subjects = Subject::orderBy('subject_name')->get();
        return view('quiz.addbutton', ['subjects' => $subjects]);
    }

    public function addcheckbox()
    {
        $subjects = Subject::orderBy('subject_name')->get();
        return view('quiz.addcheckbox', ['subjects' => $subjects]);
    }

    public function addtypeanswer()
    {
        $subjects = Subject::orderBy('subject_name')->get();
        return view('quiz.addtypeanswer', ['subjects' => $subjects]);
    }

    public function addreorder()
    {
        $subjects = Subject::orderBy('subject_name')->get();
        return view('quiz.addreorder', ['subjects' => $subjects]);
    }}
