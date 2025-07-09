<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Quiz\Subject; 
use App\Models\Quiz\QuestionType; 
use App\Models\Quiz\Topic; 
use App\Models\Quiz\Question; 
use App\Models\Quiz\Choice; 

class QuizSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     *
     * @return void
     */
    public function run()
    {
        // Nonaktifkan foreign key checks sementara untuk menghindari masalah urutan saat truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel sebelum mengisi ulang (opsional, tergantung kebutuhan)
        // Menggunakan truncate() langsung pada DB::table() karena model Eloquent tidak memiliki metode truncate()
        DB::table('choices')->truncate();
        DB::table('questions')->truncate();
        DB::table('topics')->truncate();
        DB::table('question_types')->truncate();
        DB::table('subjects')->truncate();

        // 1. Isi tabel 'subjects' menggunakan model Subject
        Subject::insert([
            ['subject_name' => 'Mathematics'],
            ['subject_name' => 'English'],
            ['subject_name' => 'Chemistry'],
            ['subject_name' => 'Computers'],
            ['subject_name' => 'Biology'],
            ['subject_name' => 'Economy'],
            ['subject_name' => 'Geography'],
            ['subject_name' => 'Physics'],
            ['subject_name' => 'Music'],
            ['subject_name' => 'Sports'],
            ['subject_name' => 'Mandarin'],
        ]);

        // 2. Isi tabel 'question_types' menggunakan model QuestionType
        QuestionType::insert([
            ['type_name' => 'Button'],
            ['type_name' => 'Checkbox'],
            ['type_name' => 'Reorder'],
            ['type_name' => 'TypeAnswer'],
        ]);

        // Ambil ID yang diperlukan untuk tabel 'topics' menggunakan model Subject
        $matematika = Subject::where('subject_name', 'Mathematics')->first()->subject_id;
        $inggris = Subject::where('subject_name', 'English')->first()->subject_id;
        $chemistry = Subject::where('subject_name', 'Chemistry')->first()->subject_id;
        $computer = Subject::where('subject_name', 'Computers')->first()->subject_id;
        $biology = Subject::where('subject_name', 'Biology')->first()->subject_id;
        $economy = Subject::where('subject_name', 'Economy')->first()->subject_id;
        $geography = Subject::where('subject_name', 'Geography')->first()->subject_id;
        $physics = Subject::where('subject_name', 'Physics')->first()->subject_id;
        $music = Subject::where('subject_name', 'Music')->first()->subject_id;
        $sports = Subject::where('subject_name', 'Sports')->first()->subject_id;
        $mandarin = Subject::where('subject_name', 'Mandarin')->first()->subject_id;

        // 3. Isi tabel 'topics' menggunakan model Topic
        Topic::insert([
            ['topic_name' => 'Algebra', 'subject_id' => $matematika],
            ['topic_name' => 'Arithmetic', 'subject_id' => $matematika],
            ['topic_name' => 'Trigonometry', 'subject_id' => $matematika],
            ['topic_name' => 'Geometry', 'subject_id' => $matematika],
            ['topic_name' => 'Calculus', 'subject_id' => $matematika],
            ['topic_name' => 'Statistics', 'subject_id' => $matematika],
            ['topic_name' => 'Vocabulary', 'subject_id' => $inggris],
            ['topic_name' => 'Grammer', 'subject_id' => $inggris],
            ['topic_name' => 'Reading', 'subject_id' => $inggris],
            ['topic_name' => 'Speaking', 'subject_id' => $inggris],
            ['topic_name' => 'Writing', 'subject_id' => $inggris],
            ['topic_name' => 'Listening', 'subject_id' => $inggris],
            ['topic_name' => 'Elements', 'subject_id' => $chemistry],
            ['topic_name' => 'Reactions', 'subject_id' => $chemistry],
            ['topic_name' => 'Acids & Bases', 'subject_id' => $chemistry],
            ['topic_name' => 'Organic', 'subject_id' => $chemistry],
            ['topic_name' => 'Stoichiometry', 'subject_id' => $chemistry],
            ['topic_name' => 'Thermochem', 'subject_id' => $chemistry],
            ['topic_name' => 'Programming', 'subject_id' => $computer],
            ['topic_name' => 'Hardware', 'subject_id' => $computer],
            ['topic_name' => 'Networking', 'subject_id' => $computer],
            ['topic_name' => 'AI', 'subject_id' => $computer],
            ['topic_name' => 'Databases', 'subject_id' => $computer],
            ['topic_name' => 'Cybersecurity', 'subject_id' => $computer],
            ['topic_name' => 'Cells', 'subject_id' => $biology],
            ['topic_name' => 'Genetics', 'subject_id' => $biology],
            ['topic_name' => 'Anatomy', 'subject_id' => $biology],
            ['topic_name' => 'Botany', 'subject_id' => $biology],
            ['topic_name' => 'Ecology', 'subject_id' => $biology],
            ['topic_name' => 'Zoology', 'subject_id' => $biology],
            ['topic_name' => 'Micro', 'subject_id' => $economy],
            ['topic_name' => 'Macro', 'subject_id' => $economy],
            ['topic_name' => 'Finance', 'subject_id' => $economy],
            ['topic_name' => 'Investing', 'subject_id' => $economy],
            ['topic_name' => 'Trade', 'subject_id' => $economy],
            ['topic_name' => 'Markets', 'subject_id' => $economy],
            ['topic_name' => 'Maps', 'subject_id' => $geography],
            ['topic_name' => 'Countries', 'subject_id' => $geography],
            ['topic_name' => 'Physical', 'subject_id' => $geography],
            ['topic_name' => 'Human', 'subject_id' => $geography],
            ['topic_name' => 'Climate', 'subject_id' => $geography],
            ['topic_name' => 'Oceans', 'subject_id' => $geography],
            ['topic_name' => 'Mechanics', 'subject_id' => $physics],
            ['topic_name' => 'Electricity', 'subject_id' => $physics],
            ['topic_name' => 'Optics', 'subject_id' => $physics],
            ['topic_name' => 'Waves', 'subject_id' => $physics],
            ['topic_name' => 'Relativity', 'subject_id' => $physics],
            ['topic_name' => 'Quantum', 'subject_id' => $physics],
            ['topic_name' => 'Theory', 'subject_id' => $music],
            ['topic_name' => 'Instruments', 'subject_id' => $music],
            ['topic_name' => 'History', 'subject_id' => $music],
            ['topic_name' => 'Composition', 'subject_id' => $music],
            ['topic_name' => 'Production', 'subject_id' => $music],
            ['topic_name' => 'Vocal', 'subject_id' => $music],
            ['topic_name' => 'Soccer', 'subject_id' => $sports],
            ['topic_name' => 'Basketball', 'subject_id' => $sports],
            ['topic_name' => 'Tennis', 'subject_id' => $sports],
            ['topic_name' => 'Athletics', 'subject_id' => $sports],
            ['topic_name' => 'Pinyin', 'subject_id' => $mandarin],
            ['topic_name' => 'Grammer', 'subject_id' => $mandarin],
            ['topic_name' => 'Conversation', 'subject_id' => $mandarin],
            ['topic_name' => 'Writing', 'subject_id' => $mandarin],
            ['topic_name' => 'Culture', 'subject_id' => $mandarin],
            ['topic_name' => 'Swimming', 'subject_id' => $sports],
            ['topic_name' => 'Stategy', 'subject_id' => $sports],
            ['topic_name' => 'Characters', 'subject_id' => $mandarin],
        ]);

        // Ambil ID yang diperlukan untuk tabel 'questions' menggunakan model Topic dan QuestionType
        $algebra = Topic::where('topic_name', 'Algebra')->first()->topic_id;
        $vocabulary = Topic::where('topic_name', 'Vocabulary')->first()->topic_id;

        $button = QuestionType::where('type_name', 'Button')->first()->q_type_id;
        $checkbox = QuestionType::where('type_name', 'Checkbox')->first()->q_type_id;
        $reorder = QuestionType::where('type_name', 'Reorder')->first()->q_type_id;
        $typeAnswer = QuestionType::where('type_name', 'TypeAnswer')->first()->q_type_id;

        // 4. Isi tabel 'questions' menggunakan model Question
        Question::insert([
            [
                'question_text' => 'Bentuk sederhana dari 5(2x-1) - (x+7) adalah…',
                'topic_id' => $algebra,
                'q_type_id' => $button,
            ],
            [
                'question_text' => 'Jika a = -3 dan b = 4, maka nilai dari 2a² - b adalah…',
                'topic_id' => $algebra,
                'q_type_id' => $button,
            ],
            [
                'question_text' => 'Penyelesaian dari persamaan 4p + 12 = 7 - p adalah…',
                'topic_id' => $algebra,
                'q_type_id' => $button,
            ],
            [
                'question_text' => 'Gradien dari garis yang melalui titik (2,1) dan (4,5) adalah…',
                'topic_id' => $algebra,
                'q_type_id' => $button,
            ],
            [
                'question_text' => 'Hasil pemfaktoran dari x² - 10x + 21 adalah…',
                'topic_id' => $algebra,
                'q_type_id' => $button,
            ],
            [
                'question_text' => 'Sederhanakan ekspresi aljabar berikut: 7y - (4y-9) = _____',
                'topic_id' => $algebra,
                'q_type_id' => $typeAnswer,
            ],
            [
                'question_text' => 'Tentukan himpunan penyelesaian dari sistem persamaan: x + y = 10 dan x - y = 4. (Tulis dalam format x=…, y=…)',
                'topic_id' => $algebra,
                'q_type_id' => $typeAnswer,
            ],
            [
                'question_text' => 'Manakah ekspresi berikut yang ekuivalen (setara) dengan (2x-3)²?',
                'topic_id' => $algebra,
                'q_type_id' => $checkbox,
            ],
            [
                'question_text' => 'Manakah di antara nilai-nilai x berikut yang merupakan solusi dari pertidaksamaan 2x - 1 ≤ 7?',
                'topic_id' => $algebra,
                'q_type_id' => $checkbox,
            ],
            [
                'question_text' => 'Urutkan langkah-langkah berikut untuk menyelesaikan persamaan 2(x - 1) = 8:',
                'topic_id' => $algebra,
                'q_type_id' => $reorder,
            ],
        ]);

        // Ambil ID yang diperlukan untuk tabel 'choices' menggunakan model Question
        $question1 = Question::where('question_text', 'Bentuk sederhana dari 5(2x-1) - (x+7) adalah…')->first()->question_id;
        $question2 = Question::where('question_text', 'Jika a = -3 dan b = 4, maka nilai dari 2a² - b adalah…')->first()->question_id;
        $question3 = Question::where('question_text', 'Penyelesaian dari persamaan 4p + 12 = 7 - p adalah…')->first()->question_id;
        $question4 = Question::where('question_text', 'Gradien dari garis yang melalui titik (2,1) dan (4,5) adalah…')->first()->question_id;
        $question5 = Question::where('question_text', 'Hasil pemfaktoran dari x² - 10x + 21 adalah…')->first()->question_id;
        $question6 = Question::where('question_text', 'Sederhanakan ekspresi aljabar berikut: 7y - (4y-9) = _____')->first()->question_id;
        $question7 = Question::where('question_text', 'Tentukan himpunan penyelesaian dari sistem persamaan: x + y = 10 dan x - y = 4. (Tulis dalam format x=…, y=…)')->first()->question_id;
        $question8 = Question::where('question_text', 'Manakah ekspresi berikut yang ekuivalen (setara) dengan (2x-3)²?')->first()->question_id;
        $question9 = Question::where('question_text', 'Manakah di antara nilai-nilai x berikut yang merupakan solusi dari pertidaksamaan 2x - 1 ≤ 7?')->first()->question_id;
        $question10 = Question::where('question_text', 'Urutkan langkah-langkah berikut untuk menyelesaikan persamaan 2(x - 1) = 8:')->first()->question_id;

        // 5. Isi tabel 'choices' menggunakan model Choice
        Choice::insert([
            // Pertanyaan 1
            ['question_id' => $question1, 'choice_text' => 'A. 9x - 12', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question1, 'choice_text' => 'B. 9x + 2', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question1, 'choice_text' => 'C. 10x - 12', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question1, 'choice_text' => 'D. 10x + 2', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 2
            ['question_id' => $question2, 'choice_text' => 'A. 14', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question2, 'choice_text' => 'B. -22', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question2, 'choice_text' => 'C. 32', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question2, 'choice_text' => 'D. -10', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 3
            ['question_id' => $question3, 'choice_text' => 'A. p = 1', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question3, 'choice_text' => 'B. p = -1', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question3, 'choice_text' => 'C. p = 5', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question3, 'choice_text' => 'D. p = -5', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 4
            ['question_id' => $question4, 'choice_text' => 'A. -2', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question4, 'choice_text' => 'B. 1/2', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question4, 'choice_text' => 'C. 2', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question4, 'choice_text' => 'D. 3', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 5
            ['question_id' => $question5, 'choice_text' => 'A. (x-3)(x-7)', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question5, 'choice_text' => 'B. (x+3)(x+7)', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question5, 'choice_text' => 'C. (x-3)(x+7)', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question5, 'choice_text' => 'D. (x+3)(x-7)', 'is_correct' => false, 'correct_order' => null],

            //Pertanyaan 6 (TypeAnswer)
            ['question_id' => $question6, 'choice_text' => '3y + 9', 'is_correct' => true, 'correct_order' => null],

            //Pertanyaan 7 (TypeAnswer)
            ['question_id' => $question7, 'choice_text' => 'x = 7, y = 3', 'is_correct' => true, 'correct_order' => null],

            // Pertanyaan 8 (Checkbox)
            ['question_id' => $question8, 'choice_text' => 'A. 4x² - 9', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question8, 'choice_text' => 'B. (2x -3)(2x -3)', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question8, 'choice_text' => 'C. 4x² + 12x + 9', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question8, 'choice_text' => 'D. 4x² - 12x + 9', 'is_correct' => true, 'correct_order' => null],

            // Pertanyaan 9 (Checkbox)
            ['question_id' => $question9, 'choice_text' => 'A. x = 5', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question9, 'choice_text' => 'B. x = 4', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question9, 'choice_text' => 'C. x = -2', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question9, 'choice_text' => 'D. x = 4,5', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 10 (Reorder)
            ['question_id' => $question10, 'choice_text' => 'B. Tuliskan persamaan awal', 'is_correct' => false, 'correct_order' => 1],
            ['question_id' => $question10, 'choice_text' => 'A. Bagi kedua sisi dengan 2', 'is_correct' => false, 'correct_order' => 2],
            ['question_id' => $question10, 'choice_text' => 'C. Tambahkan 1 kedua sisi', 'is_correct' => false, 'correct_order' => 3],
            ['question_id' => $question10, 'choice_text' => 'D. Dapatkan solusi akhir x = 5', 'is_correct' => false, 'correct_order' => 4], // Mengoreksi '55' menjadi '5'
        ]);

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
