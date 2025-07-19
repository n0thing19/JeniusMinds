<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Question;
use App\Models\Quiz\Choice;
use App\Models\Quiz\QuestionType;
use App\Models\Quiz\Subject;
use App\Models\Quiz\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Menampilkan halaman utama editor kuis atau halaman edit.
     */
    public function editor(Request $request)
    {
        if ($request->has('topic_id')) {
            $topic = Topic::findOrFail($request->query('topic_id'));
            return $this->edit($topic);
        }

        $subjects = Subject::orderBy('subject_name')->get();
        return view('quiz.editor', [
            'subjects' => $subjects,
            'topic' => null,
            'existingQuestions' => null,
        ]);
    }

    /**
     * Menyiapkan data untuk mengedit kuis yang sudah ada.
     */
    public function edit(Topic $topic)
    {
        if ($topic->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $topic->load('questions.choices', 'questions.questionType');
        $subjects = Subject::orderBy('subject_name')->get();

        $formattedQuestions = $topic->questions->map(function ($question) {
            $choices = array_pad($question->choices->pluck('choice_text')->toArray(), 4, '');
            $data = [
                'q_type_name' => $question->questionType->type_name,
                'question_text' => $question->question_text,
                'choices' => $choices,
            ];

            if ($data['q_type_name'] === 'Button') {
                $data['correct_choice'] = $question->choices->search(fn($choice) => $choice->is_correct) ?: -1;
            } elseif ($data['q_type_name'] === 'Checkbox') {
                $data['correct_choices'] = $question->choices->filter(fn($choice) => $choice->is_correct)->keys()->toArray();
            } elseif ($data['q_type_name'] === 'Reorder') {
                $data['correct_order'] = $question->choices->pluck('correct_order')->toArray();
            } elseif ($data['q_type_name'] === 'TypeAnswer') {
                $data['correct_answer'] = $question->choices->first()->choice_text ?? '';
            }

            return $data;
        });

        return view('quiz.editor', [
            'topic' => $topic,
            'subjects' => $subjects,
            'existingQuestions' => $formattedQuestions->toJson()
        ]);
    }


    public function update(Request $request, Topic $topic)
    {
        // 1. Validasi Keamanan: Pastikan pengguna hanya bisa mengedit kuis miliknya.
        if ($topic->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Validasi Input
        $validatedData = $request->validate([
            'new_topic_name' => 'required|string|max:255|min:3',
            'subject_id'     => 'required|exists:subjects,subject_id',
            'questions_data' => 'required|json'
        ]);

        $questionsData = json_decode($validatedData['questions_data'], true);

        // Periksa jika data pertanyaan kosong setelah di-decode
        if (empty($questionsData)) {
            return redirect()->back()->withErrors(['main' => 'Cannot save an empty quiz. Please add at least one question.']);
        }

        // Memulai transaksi database untuk memastikan semua operasi berhasil atau tidak sama sekali.
        DB::beginTransaction();

        try {
            // 3. Update detail Topik (nama kuis dan mata pelajaran)
            $topic->update([
                'topic_name' => $validatedData['new_topic_name'],
                'subject_id' => $validatedData['subject_id'],
            ]);

            // 4. HAPUS SEMUA PERTANYAAN LAMA YANG TERKAIT DENGAN TOPIK INI
            // Ini adalah langkah kunci. Pastikan relasi di model Anda diatur untuk 'cascade on delete'
            // atau Laravel akan menghapusnya secara manual.
            $topic->questions()->delete();

            // Ambil ID tipe pertanyaan untuk efisiensi
            $questionTypes = QuestionType::pluck('q_type_id', 'type_name');

            // 5. LOOPING DAN BUAT ULANG SEMUA PERTANYAAN DARI DATA BARU
            foreach ($questionsData as $q_data) {
                // Buat pertanyaan baru
                $newQuestion = Question::create([
                    'topic_id'      => $topic->topic_id,
                    'question_text' => $q_data['question_text'] ?? 'Untitled Question',
                    'q_type_id'     => $questionTypes[$q_data['q_type_name']] ?? null,
                ]);

                // Loop untuk menyimpan pilihan jawaban (choices) untuk pertanyaan ini
                if (isset($q_data['choices']) && is_array($q_data['choices'])) {
                    foreach ($q_data['choices'] as $index => $choiceText) {
                        if (!empty($choiceText)) {
                            Choice::create([
                                'question_id' => $newQuestion->question_id,
                                'choice_text' => $choiceText,
                                // Tentukan apakah pilihan ini benar
                                'is_correct'  => isset($q_data['correct_choice']) && $q_data['correct_choice'] == $index,
                            ]);
                        }
                    }
                }
            }

            // Jika semua berhasil, konfirmasi transaksi
            DB::commit();

            return redirect()->route('profile.show')->with('success', 'Quiz "' . $topic->topic_name . '" has been updated successfully!');

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua perubahan
            DB::rollBack();
            Log::error("Failed to update quiz: " . $e->getMessage()); // Log error untuk debugging
            return redirect()->back()->withErrors(['main' => 'An error occurred while updating the quiz. Please try again.']);
        }
    }

        public function destroy(Topic $topic)
    {
        // 1. Validasi Keamanan: Pastikan pengguna hanya bisa menghapus kuis miliknya.
        if ($topic->user_id !== Auth::id()) {
            // Jika pengguna mencoba menghapus kuis orang lain, kembalikan error 403 (Forbidden).
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        // Memulai transaksi database untuk memastikan integritas data.
        DB::beginTransaction();

        try {
            // Simpan nama topik untuk pesan sukses sebelum dihapus.
            $topicName = $topic->topic_name;

            // 2. Hapus Topik.
            // Karena kita sudah mengatur 'cascade on delete' di model,
            // Laravel akan secara otomatis menghapus semua pertanyaan (questions)
            // dan pilihan jawaban (choices) yang terkait dengan topik ini.
            $topic->delete();

            // 3. Konfirmasi transaksi jika penghapusan berhasil.
            DB::commit();

            // 4. Arahkan kembali ke halaman profil dengan pesan sukses.
            return redirect()->route('profile.show')->with('success', 'Quiz "' . $topicName . '" has been successfully deleted.');

        } catch (\Exception $e) {
            // Jika terjadi error selama proses, batalkan semua perubahan.
            DB::rollBack();

            // Catat error untuk debugging.
            Log::error("Failed to delete quiz topic: " . $e->getMessage());

            // Arahkan kembali dengan pesan error.
            return redirect()->route('profile.show')->withErrors(['main' => 'An error occurred while deleting the quiz. Please try again.']);
        }
    }

        public function start(Topic $topic)
    {
        $topic->load('questions.choices', 'questions.questionType');





        $quizData = [
            'topic_id' => $topic->topic_id,
            'topic_name' => $topic->topic_name,
            'questions' => $topic->questions->map(function ($question) {
                return [
                    'id' => $question->question_id,
                    'question_text' => $question->question_text,
                    'q_type_name' => $question->questionType->type_name,
                    'choices' => $question->choices->map(function ($choice) {
                        return ['id' => $choice->choice_id, 'choice_text' => $choice->choice_text];
                    })->all(),



                ];
            })->all(),
        ];
        return view('quiz.show', ['topic' => $topic, 'quizData' => $quizData]);






    }

    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'topic_id' => 'required|exists:topics,topic_id',
                'answers' => 'required|array',
            ]);

            $userAnswersPayload = $validated['answers'];
            $topicId = $validated['topic_id'];




            $topic = Topic::with('questions.choices', 'questions.questionType')->findOrFail($topicId);
            
            $score = 0;



            foreach ($topic->questions as $question) {
                $questionId = $question->question_id;
                $userAnswer = $userAnswersPayload[$questionId] ?? null;

                if ($userAnswer === null) {
                    continue;
                }

                $isCorrect = false;

                switch ($question->questionType->type_name) {
                    case 'Button':
                        $correctChoiceId = $question->choices->where('is_correct', true)->first()?->choice_id;
                        $isCorrect = ((int)$userAnswer === (int)$correctChoiceId);
                        break;

                    case 'TypeAnswer':
                        $correctChoice = $question->choices->where('is_correct', true)->first();
                        $correctAnswerText = $correctChoice?->choice_text;

                        // Log detail untuk debugging TypeAnswer
                        Log::info("--- TypeAnswer Check for QID: {$questionId} ---", [
                            '1_user_answer_raw' => $userAnswer,
                            '2_user_answer_type' => gettype($userAnswer),
                            '3_correct_choice_object' => $correctChoice ? $correctChoice->toArray() : 'null',
                            '4_correct_answer_text_raw' => $correctAnswerText,
                            '5_correct_answer_type' => gettype($correctAnswerText)
                        ]);
                        
                        if (is_string($userAnswer) && $correctAnswerText !== null) {
                            $isCorrect = (strcasecmp(trim($userAnswer), trim($correctAnswerText)) === 0);
                        } else {
                            $isCorrect = false;
                        }
                        break;

                    case 'Checkbox':
                        $correctChoiceIds = $question->choices->where('is_correct', true)->pluck('choice_id')->toArray();
                        if (!is_array($userAnswer)) continue 2;
                        
                        $userAnswerInt = array_map('intval', $userAnswer);
                        
                        sort($userAnswerInt);
                        sort($correctChoiceIds);
                        
                        $isCorrect = ($userAnswerInt == $correctChoiceIds);
                        break;
                    
                    case 'Reorder':
                        $correctOrderMap = $question->choices->pluck('correct_order', 'choice_id')->filter()->toArray();
                        
                        if (!is_array($userAnswer) || count($userAnswer) != count($correctOrderMap)) {
                            continue 2;
                        }
                        
                        $userOrderMap = collect($userAnswer)->pluck('order', 'choice_id');
                        
                        $isCorrect = true;
                        foreach ($correctOrderMap as $choiceId => $correctOrder) {
                            if (!isset($userOrderMap[$choiceId]) || (int)$userOrderMap[$choiceId] != (int)$correctOrder) {
                                $isCorrect = false;
                                break;
                            }
                        }
                        break;
                }

                if ($isCorrect) {
                    $score++;
                }
            }

            return response()->json([
                'message' => 'Quiz submitted successfully!',
                'score' => $score,
                'total_questions' => $topic->questions->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Quiz Submission Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return response()->json([
                'message' => 'An error occurred on the server while processing the quiz.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan kuis baru dan semua pertanyaannya.
     */
    public function storeAll(Request $request)
    {
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
            $newTopic = Topic::create([
                'topic_name' => $validatedData['new_topic_name'],
                'subject_id' => $validatedData['subject_id'],
                'user_id' => Auth::id(),
            ]);
            $topicId = $newTopic->topic_id;

            foreach ($questionsData as $index => $data) {
                $question = Question::create([
                    'question_text' => $data['question_text'],
                    'topic_id' => $topicId,
                    'q_type_id' => $questionTypes[$data['q_type_name']] ?? null,
                ]);

                if ($data['q_type_name'] === 'Button') {
                    if (!isset($data['correct_choice']) || $data['correct_choice'] < 0) throw new \Exception("Correct answer must be selected for question #" . ($index + 1));
                    $correctIndex = $data['correct_choice'];
                    foreach ($data['choices'] as $choiceIndex => $choiceText) {
                        if (empty($choiceText)) continue;
                        Choice::create(['question_id' => $question->question_id, 'choice_text' => $choiceText, 'is_correct' => ($choiceIndex == $correctIndex)]);
                    }
                } elseif ($data['q_type_name'] === 'Checkbox') {
                    if (!isset($data['correct_choices']) || !is_array($data['correct_choices']) || empty($data['correct_choices'])) throw new \Exception("At least one correct answer must be selected for question #" . ($index + 1));
                    $correctIndices = $data['correct_choices'];
                    foreach ($data['choices'] as $choiceIndex => $choiceText) {
                         if (empty($choiceText)) continue;
                        Choice::create(['question_id' => $question->question_id, 'choice_text' => $choiceText, 'is_correct' => in_array($choiceIndex, $correctIndices)]);
                    }
                } elseif ($data['q_type_name'] === 'TypeAnswer') {
                    Choice::create(['question_id' => $question->question_id, 'choice_text' => $data['choices'][0], 'is_correct' => true]);
                } elseif ($data['q_type_name'] === 'Reorder') {
                    if (!isset($data['correct_order']) || count(array_filter($data['correct_order'], fn($v) => $v != -1)) !== count(array_filter($data['choices']))) throw new \Exception("All items must be ranked for reorder question #" . ($index + 1));
                    $correctOrder = $data['correct_order'];
                    foreach ($data['choices'] as $choiceIndex => $choiceText) {
                         if (empty($choiceText)) continue;
                        Choice::create(['question_id' => $question->question_id, 'choice_text' => $choiceText, 'is_correct' => false, 'correct_order' => $correctOrder[$choiceIndex] ?? null]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('home')->with('success', 'Quiz "' . $newTopic->topic_name . '" has been created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to save quiz: " . $e->getMessage());
            return redirect()->back()->withErrors(['main' => 'An error occurred: ' . $e->getMessage()]);
        }
    }


    /**
     * Menampilkan halaman pengerjaan kuis.
     */
    public function start(Topic $topic)
    {
        $topic->load('questions.choices', 'questions.questionType');
        $quizData = [
            'topic_id' => $topic->topic_id,
            'topic_name' => $topic->topic_name,
            'questions' => $topic->questions->map(function ($question) {
                return [
                    'id' => $question->question_id,
                    'question_text' => $question->question_text,
                    'q_type_name' => $question->questionType->type_name,
                    'choices' => $question->choices->map(function ($choice) {
                        return ['id' => $choice->choice_id, 'choice_text' => $choice->choice_text];
                    })->all(),
                ];
            })->all(),
        ];
        return view('quiz.show', ['topic' => $topic, 'quizData' => $quizData]);
    }

    /**
     * Menerima, memeriksa, dan menilai jawaban kuis dari user.
     */
    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'topic_id' => 'required|exists:topics,topic_id',
                'answers' => 'required|array',
            ]);

            $userAnswersPayload = $validated['answers'];
            $topicId = $validated['topic_id'];

            $topic = Topic::with('questions.choices', 'questions.questionType')->findOrFail($topicId);
            
            $score = 0;

            foreach ($topic->questions as $question) {
                $questionId = $question->question_id;
                $userAnswer = $userAnswersPayload[$questionId] ?? null;

                if ($userAnswer === null) {
                    continue;
                }

                $isCorrect = false;

                switch ($question->questionType->type_name) {
                    case 'Button':
                        $correctChoiceId = $question->choices->where('is_correct', true)->first()?->choice_id;
                        $isCorrect = ((int)$userAnswer === (int)$correctChoiceId);
                        break;

                    case 'TypeAnswer':
                        $correctChoice = $question->choices->where('is_correct', true)->first();
                        $correctAnswerText = $correctChoice?->choice_text;

                        // Log detail untuk debugging TypeAnswer
                        Log::info("--- TypeAnswer Check for QID: {$questionId} ---", [
                            '1_user_answer_raw' => $userAnswer,
                            '2_user_answer_type' => gettype($userAnswer),
                            '3_correct_choice_object' => $correctChoice ? $correctChoice->toArray() : 'null',
                            '4_correct_answer_text_raw' => $correctAnswerText,
                            '5_correct_answer_type' => gettype($correctAnswerText)
                        ]);
                        
                        if (is_string($userAnswer) && $correctAnswerText !== null) {
                            $isCorrect = (strcasecmp(trim($userAnswer), trim($correctAnswerText)) === 0);
                        } else {
                            $isCorrect = false;
                        }
                        break;

                    case 'Checkbox':
                        $correctChoiceIds = $question->choices->where('is_correct', true)->pluck('choice_id')->toArray();
                        if (!is_array($userAnswer)) continue 2;
                        
                        $userAnswerInt = array_map('intval', $userAnswer);
                        
                        sort($userAnswerInt);
                        sort($correctChoiceIds);
                        
                        $isCorrect = ($userAnswerInt == $correctChoiceIds);
                        break;
                    
                    case 'Reorder':
                        $correctOrderMap = $question->choices->pluck('correct_order', 'choice_id')->filter()->toArray();
                        
                        if (!is_array($userAnswer) || count($userAnswer) != count($correctOrderMap)) {
                            continue 2;
                        }
                        
                        $userOrderMap = collect($userAnswer)->pluck('order', 'choice_id');
                        
                        $isCorrect = true;
                        foreach ($correctOrderMap as $choiceId => $correctOrder) {
                            if (!isset($userOrderMap[$choiceId]) || (int)$userOrderMap[$choiceId] != (int)$correctOrder) {
                                $isCorrect = false;
                                break;
                            }
                        }
                        break;
                }

                if ($isCorrect) {
                    $score++;
                }
            }

            return response()->json([
                'message' => 'Quiz submitted successfully!',
                'score' => $score,
                'total_questions' => $topic->questions->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Quiz Submission Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return response()->json([
                'message' => 'An error occurred on the server while processing the quiz.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

    private function getEditorViewData(Request $request, string $viewName)
    {
        $subjects = Subject::orderBy('subject_name')->get();
        $topic = null; // Defaultnya null (untuk mode buat baru)

        // Periksa apakah ada 'topic_id' di query string URL
        if ($request->has('topic_id')) {
            // Temukan topik berdasarkan ID
            $topic = Topic::findOrFail($request->query('topic_id'));

            // Validasi keamanan: pastikan pengguna hanya bisa mengedit kuis miliknya
            if ($topic->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
        }

        // Kirim data ke view yang ditentukan
        return view($viewName, [
            'subjects' => $subjects,
            'topic'    => $topic, // Variabel $topic ini akan selalu ada (berisi objek atau null)
        ]);
    }

    public function addbutton(Request $request)
    {
        // Ganti implementasi lama dengan memanggil metode privat baru
        return $this->getEditorViewData($request, 'quiz.addbutton');
    }

    public function addcheckbox(Request $request)
    {
        // Ganti implementasi lama dengan memanggil metode privat baru
        return $this->getEditorViewData($request, 'quiz.addcheckbox');
    }

    public function addtypeanswer(Request $request)
    {
        // Ganti implementasi lama dengan memanggil metode privat baru
        return $this->getEditorViewData($request, 'quiz.addtypeanswer');
    }

    public function addreorder(Request $request)
    {
        // Ganti implementasi lama dengan memanggil metode privat baru
        return $this->getEditorViewData($request, 'quiz.addreorder');
    }
}

