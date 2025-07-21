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
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

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
            ['topic_name' => 'Grammar', 'subject_id' => $inggris],
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
            ['topic_name' => 'Grammar', 'subject_id' => $mandarin],
            ['topic_name' => 'Conversation', 'subject_id' => $mandarin],
            ['topic_name' => 'Writing', 'subject_id' => $mandarin],
            ['topic_name' => 'Culture', 'subject_id' => $mandarin],
            ['topic_name' => 'Swimming', 'subject_id' => $sports],
            ['topic_name' => 'Strategy', 'subject_id' => $sports],
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

            [
                'question_text' => 'The manager was **candid** about the companys financial problems. (Choose the word that is closest in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'Her **diligent** work on the project paid off with a promotion. (Choose the word that is closest in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'Smartphones have become **ubiquitous** in modern society. (Choose the word that is closest in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'The monk lived an **austere** life with few possessions. (Choose the word that is closest in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'The **benevolent** king was loved by all his subjects. (Choose the word that is closest in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'The rainforest has an **abundant** supply of water. (Choose the word that is opposite in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'The company plans to **expand** its operations next year. (Choose the word that is opposite in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'He is a **frequent** visitor to the local library. (Choose the word that is opposite in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'The horse was **docile** and easy for the children to ride. (Choose the word that is opposite in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'The water in the lake was so clear it was almost **transparant**. (Choose the word that is opposite in meaning to the bold word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $button,
            ],

            [
                'question_text' => 'A good leader must have ________ for the people they are leading. (Fill in the blank with the most appropriate word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $typeAnswer,
            ],

            [
                'question_text' => 'Despite facing many setbacks, the team remained ________ and eventually succeeded. (Fill in the blank with the most appropriate word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $typeAnswer,
            ],            

            [
                'question_text' => 'The instructions for the device were ________, which made it difficult to assemble. (Fill in the blank with the most appropriate word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $typeAnswer,
            ], 

            [
                'question_text' => 'Looking at old photographs often fills me with a sense of ________. (Fill in the blank with the most appropriate word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $typeAnswer,
            ],            

            [
                'question_text' => 'After the big meal, I felt ________ and just wanted to take a nap. (Fill in the blank with the most appropriate word)',
                'topic_id' => $vocabulary,
                'q_type_id' => $typeAnswer,
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
        $question11 = Question::where('question_text', 'The manager was **candid** about the companys financial problems. (Choose the word that is closest in meaning to the bold word)')->first()->question_id;
        $question12 = Question::where('question_text', 'Her **diligent** work on the project paid off with a promotion. (Choose the word that is closest in meaning to the bold word)')->first()->question_id;
        $question13 = Question::where('question_text', 'Smartphones have become **ubiquitous** in modern society. (Choose the word that is closest in meaning to the bold word)')->first()->question_id;
        $question14 = Question::where('question_text', 'The monk lived an **austere** life with few possessions. (Choose the word that is closest in meaning to the bold word)')->first()->question_id;
        $question15 = Question::where('question_text', 'The **benevolent** king was loved by all his subjects. (Choose the word that is closest in meaning to the bold word)')->first()->question_id;
        $question16 = Question::where('question_text', 'The rainforest has an **abundant** supply of water. (Choose the word that is opposite in meaning to the bold word)')->first()->question_id;
        $question17 = Question::where('question_text', 'The company plans to **expand** its operations next year. (Choose the word that is opposite in meaning to the bold word)')->first()->question_id;
        $question18 = Question::where('question_text', 'He is a **frequent** visitor to the local library. (Choose the word that is opposite in meaning to the bold word)')->first()->question_id;
        $question19 = Question::where('question_text', 'The horse was **docile** and easy for the children to ride. (Choose the word that is opposite in meaning to the bold word)')->first()->question_id;
        $question20 = Question::where('question_text', 'The water in the lake was so clear it was almost **transparant**. (Choose the word that is opposite in meaning to the bold word)')->first()->question_id;
        $question21 = Question::where('question_text', 'A good leader must have ________ for the people they are leading. (Fill in the blank with the most appropriate word)')->first()->question_id;
        $question22 = Question::where('question_text', 'Despite facing many setbacks, the team remained ________ and eventually succeeded. (Fill in the blank with the most appropriate word)')->first()->question_id;
        $question23 = Question::where('question_text', 'The instructions for the device were ________, which made it difficult to assemble. (Fill in the blank with the most appropriate word)')->first()->question_id;
        $question24 = Question::where('question_text', 'Looking at old photographs often fills me with a sense of ________. (Fill in the blank with the most appropriate word)')->first()->question_id;
        $question25 = Question::where('question_text', 'After the big meal, I felt ________ and just wanted to take a nap. (Fill in the blank with the most appropriate word)')->first()->question_id;

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
            ['question_id' => $question10, 'choice_text' => 'Tuliskan persamaan awal', 'is_correct' => false, 'correct_order' => 1],
            ['question_id' => $question10, 'choice_text' => 'Bagi kedua sisi dengan 2', 'is_correct' => false, 'correct_order' => 2],
            ['question_id' => $question10, 'choice_text' => 'Tambahkan 1 kedua sisi', 'is_correct' => false, 'correct_order' => 3],
            ['question_id' => $question10, 'choice_text' => 'Dapatkan solusi akhir x = 5', 'is_correct' => false, 'correct_order' => 4],
            // Pertanyaan 11
            ['question_id' => $question11, 'choice_text' => 'A. Secretive', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question11, 'choice_text' => 'B. Rude', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question11, 'choice_text' => 'C. Frank', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question11, 'choice_text' => 'D. Quiet', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 12
            ['question_id' => $question12, 'choice_text' => 'A. Industrious', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question12, 'choice_text' => 'B. Smart', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question12, 'choice_text' => 'C. Lazy', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question12, 'choice_text' => 'D. Quick', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 13
            ['question_id' => $question13, 'choice_text' => 'A. Rare', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question13, 'choice_text' => 'B. Omnipresent', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question13, 'choice_text' => 'C. Hidden', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question13, 'choice_text' => 'D. Famous', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 14
            ['question_id' => $question14, 'choice_text' => 'A. Simple', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question14, 'choice_text' => 'B. Friendly', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question14, 'choice_text' => 'C. Decorated', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question14, 'choice_text' => 'D. Luxurious', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 15
            ['question_id' => $question15, 'choice_text' => 'A. Hostile', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question15, 'choice_text' => 'B. Selfish', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question15, 'choice_text' => 'C. Charitable', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question15, 'choice_text' => 'D. Greedy', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 16
            ['question_id' => $question16, 'choice_text' => 'A. Plenty', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question16, 'choice_text' => 'B. Scarce', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question16, 'choice_text' => 'C. Rich', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question16, 'choice_text' => 'D. Many', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 17
            ['question_id' => $question17, 'choice_text' => 'A. Grow', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question17, 'choice_text' => 'B. Stretch', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question17, 'choice_text' => 'C. Contract', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question17, 'choice_text' => 'D. Open', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 18
            ['question_id' => $question18, 'choice_text' => 'A. Rare', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question18, 'choice_text' => 'B. Usual', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question18, 'choice_text' => 'C. Common', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question18, 'choice_text' => 'D. Constant', 'is_correct' => false, 'correct_order' => null],

            // Pertanyaan 19
            ['question_id' => $question19, 'choice_text' => 'A. Obedient', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question19, 'choice_text' => 'B. Gentle', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question19, 'choice_text' => 'C. Calm', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question19, 'choice_text' => 'D. Unruly', 'is_correct' => true, 'correct_order' => null],

            // Pertanyaan 20
            ['question_id' => $question20, 'choice_text' => 'A. Opaque', 'is_correct' => true, 'correct_order' => null],
            ['question_id' => $question20, 'choice_text' => 'B. Clear', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question20, 'choice_text' => 'C. Visible', 'is_correct' => false, 'correct_order' => null],
            ['question_id' => $question20, 'choice_text' => 'D. Lucid', 'is_correct' => false, 'correct_order' => null],

            //Pertanyaan 21
            ['question_id' => $question21, 'choice_text' => 'Empathy', 'is_correct' => true, 'correct_order' => null],

            //Pertanyaan 22
            ['question_id' => $question22, 'choice_text' => 'Resilient', 'is_correct' => true, 'correct_order' => null],

            //Pertanyaan 23
            ['question_id' => $question23, 'choice_text' => 'Ambiguous', 'is_correct' => true, 'correct_order' => null],

            //Pertanyaan 24
            ['question_id' => $question24, 'choice_text' => 'Nostalgia', 'is_correct' => true, 'correct_order' => null],

            //Pertanyaan 25
            ['question_id' => $question25, 'choice_text' => 'Lethargic', 'is_correct' => true, 'correct_order' => null],
        ]);

        // Aktifkan kembali foreign key checks
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }
}
