<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz\Question;
use App\Models\Quiz\Choice;
use App\Models\Quiz\QuestionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Quiz\Topic;


class QuizController extends Controller
{
    public function editor(){
        return view('quiz.editor');
    }
    public function addbutton(){
        return view('quiz.addbutton');
    }
    public function addcheckbox(){
        return view('quiz.addcheckbox');
    }
    public function addtypeanswer(){
        return view('quiz.addtypeanswer');
    }
    public function addreorder(){
        return view('quiz.addreorder');
    }

public function storeAll(Request $request)
{
    // Validasi input utama: data JSON harus ada dan topic_id harus dipilih
    $validatedData = $request->validate([
        'questions_data' => 'required|json',
        'topic_id' => 'required|exists:topics,topic_id'
    ], [
        'questions_data.required' => 'There are no questions to save.',
        'topic_id.required' => 'You must select a topic for the quiz before saving.'
    ]);

    $questionsData = json_decode($validatedData['questions_data'], true);
    $topicId = $validatedData['topic_id'];

    if (empty($questionsData)) {
        return redirect()->route('quiz.editor')->withErrors(['main' => 'No questions were provided to save.']);
    }

    $questionTypes = QuestionType::pluck('q_type_id', 'type_name');

    DB::beginTransaction();
    try {
        foreach ($questionsData as $index => $data) {
            // Validasi setiap soal di dalam array JSON
            $validator = Validator::make($data, [
                'question_text' => 'required|string|min:3',
                'q_type_name' => 'required|string', // Pastikan tipe soal ada
                'choices' => 'required|array|min:4',
                'choices.*' => 'required|string|min:1',
                'correct_choice' => 'required|integer'
            ]);

            // Jika validasi untuk satu soal gagal, batalkan semua dan kirim pesan error
            if ($validator->fails()) {
                throw new \Exception("Validation failed for question #" . ($index + 1) . ": " . $validator->errors()->first());
            }
            
            // Buat pertanyaan
            $question = Question::create([
                'question_text' => $data['question_text'],
                'topic_id' => $topicId, // Gunakan topic_id yang sama untuk semua
                'q_type_id' => $questionTypes[$data['q_type_name']] ?? null,
            ]);

            // Simpan pilihan jawaban
            foreach ($data['choices'] as $choiceIndex => $choiceText) {
                Choice::create([
                    'question_id' => $question->question_id,
                    'choice_text' => $choiceText,
                    'is_correct' => ($choiceIndex === $data['correct_choice']),
                    'correct_order' => ($data['q_type_name'] === 'reorder') ? $choiceIndex : null, // Hanya untuk tipe soal reorder
                    // Anda bisa menambahkan 'correct_order' di sini untuk tipe soal reorder
                ]);
            }
        }

        // Jika semua berhasil
        DB::commit();
        // Beri pesan sukses dan hapus data dari session di sisi client
        return redirect()->route('quiz.editor')->with('success', count($questionsData) . ' questions have been saved successfully!');

    } catch (\Exception $e) {
        // Jika ada error di tengah jalan
        DB::rollBack();
        Log::error("Failed to save all questions: " . $e->getMessage());
        // Kembalikan ke halaman editor dengan pesan error yang jelas
        return redirect()->route('quiz.editor')->withErrors(['main' => 'An error occurred: ' . $e->getMessage()]);
    }
}

    public function start(Topic $topic)
    {
        // Menggunakan Eager Loading untuk mengambil semua relasi yang dibutuhkan
        // Ini sangat efisien dan mencegah masalah N+1 query.
        $topic->load('questions.choices');

        // Menyiapkan data dalam format yang dibutuhkan oleh Alpine.js di frontend
        $quizData = [
            'topic_name' => $topic->topic_name,
            'questions' => $topic->questions->map(function ($question) {
                return [
                    'id' => $question->question_id,
                    'question_text' => $question->question_text,
                    // Mengambil hanya kolom yang diperlukan dari pilihan jawaban
                    'choices' => $question->choices->map(function ($choice) {
                        return [
                            'id' => $choice->choice_id,
                            'choice_text' => $choice->choice_text,
                        ];
                    }),
                ];
            }),
        ];

        // Mengirim data ke view
        return view('quiz.show', [
            'topic' => $topic,
            'quizData' => $quizData,
        ]);
    }

    /**
     * Memproses jawaban kuis yang telah disubmit oleh pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'topic_id' => 'required|exists:topics,topic_id',
            'answers' => 'required|json',
        ]);

        $userAnswers = json_decode($request->answers, true);
        $topicId = $request->topic_id;

        // Di sini Anda akan menambahkan logika untuk menghitung skor.
        // 1. Ambil semua jawaban yang benar untuk kuis ini dari database.
        // 2. Bandingkan jawaban pengguna dengan jawaban yang benar.
        // 3. Hitung skor, akurasi, dll.
        // 4. Simpan hasil kuis ke database.

        // Untuk sekarang, kita hanya akan menampilkan hasilnya di console (log)
        // dan mengarahkan ke halaman summary (yang akan dibuat nanti).
        // \Log::info('Quiz Submitted for Topic ID: ' . $topicId);
        // \Log::info('User Answers: ', $userAnswers);

        // Arahkan ke halaman hasil/summary dengan membawa ID hasil kuis
        // return redirect()->route('quiz.summary', ['result_id' => $newResult->id]);
        
        // Untuk sementara, kita arahkan kembali ke homepage
        return redirect('/')->with('status', 'Quiz submitted successfully!');
    }

}