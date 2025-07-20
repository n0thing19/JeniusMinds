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
use Illuminate\Support\Str; // <-- 1. PASTIKAN USE STATEMENT INI ADA

class QuizController extends Controller
{
    // ... (Semua metode lain seperti editor, edit, update, destroy, dll. tidak ada perubahan) ...

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

    public function edit(Topic $topic)
    {
        if ($topic->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $topic->load('questions.choices', 'questions.questionType');
        $subjects = Subject::orderBy('subject_name')->get();

        $formattedQuestions = $topic->questions->map(function ($question) {
            $choices = $question->choices->pluck('choice_text')->toArray();
            $choices = array_pad($choices, 4, '');

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
            } elseif ($data['q_type_name'] === 'TextAnswer') {
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
        if ($topic->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'new_topic_name' => 'required|string|max:255|min:3',
            'subject_id'     => 'required|exists:subjects,subject_id',
            'questions_data' => 'required|json'
        ]);

        $questionsData = json_decode($validatedData['questions_data'], true);

        if (empty($questionsData)) {
            return redirect()->back()->withErrors(['main' => 'Cannot save an empty quiz. Please add at least one question.']);
        }

        DB::beginTransaction();

        try {
            $topic->update([
                'topic_name' => $validatedData['new_topic_name'],
                'subject_id' => $validatedData['subject_id'],
            ]);

            $topic->questions()->delete();

            $questionTypes = QuestionType::pluck('q_type_id', 'type_name');

            foreach ($questionsData as $q_data) {
                $newQuestion = Question::create([
                    'topic_id'      => $topic->topic_id,
                    'question_text' => $q_data['question_text'] ?? 'Untitled Question',
                    'q_type_id'     => $questionTypes[$q_data['q_type_name']] ?? null,
                ]);

                if (isset($q_data['choices']) && is_array($q_data['choices'])) {
                    foreach ($q_data['choices'] as $index => $choiceText) {
                        if (!empty($choiceText)) {
                            Choice::create([
                                'question_id' => $newQuestion->question_id,
                                'choice_text' => $choiceText,
                                'is_correct'  => isset($q_data['correct_choice']) && $q_data['correct_choice'] == $index,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('profile.show')->with('success', 'Quiz "' . $topic->topic_name . '" has been updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update quiz: " . $e->getMessage());
            return redirect()->back()->withErrors(['main' => 'An error occurred while updating the quiz. Please try again.']);
        }
    }

    public function destroy(Topic $topic)
    {
        if ($topic->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        DB::beginTransaction();

        try {
            $topicName = $topic->topic_name;
            $topic->delete();
            DB::commit();
            return redirect()->route('profile.show')->with('success', 'Quiz "' . $topicName . '" has been successfully deleted.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete quiz topic: " . $e->getMessage());
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
     * Menyimpan topik baru dan semua pertanyaan dari quiz editor.
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
            // 2. BUAT KODE UNIK
            do {
                $code = Str::random(6);
            } while (Topic::where('code', $code)->exists());


            // 3. BUAT TOPIK BARU DENGAN KODE UNIK
            $newTopic = Topic::create([
                'topic_name' => $validatedData['new_topic_name'],
                'subject_id' => $validatedData['subject_id'],
                'user_id' => Auth::id(),
                'code' => $code, // <-- Tambahkan kode yang baru dibuat
            ]);

            $topicId = $newTopic->topic_id;

            foreach ($questionsData as $index => $data) {
                $question = Question::create([
                    'question_text' => $data['question_text'],
                    'topic_id' => $topicId,
                    'q_type_id' => $questionTypes[$data['q_type_name']] ?? null,
                ]);

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
                    Choice::create([
                        'question_id' => $question->question_id,
                        'choice_text' => $data['choices'][0],
                        'is_correct' => true,
                    ]);
                } elseif ($data['q_type_name'] === 'Reorder') {
                    if (!isset($data['correct_order']) || count(array_filter($data['correct_order'], fn($v) => $v != -1)) !== 4) {
                        throw new \Exception("All items must be ranked for reorder question #" . ($index + 1));
                    }
                    $correctOrder = $data['correct_order'];
                    foreach ($data['choices'] as $choiceIndex => $choiceText) {
                        Choice::create([
                            'question_id' => $question->question_id,
                            'choice_text' => $choiceText,
                            'is_correct' => false,
                            'correct_order' => $correctOrder[$choiceIndex] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('profile.show')->with('success', 'Quiz "' . $newTopic->topic_name . '" has been created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to save all questions: " . $e->getMessage());
            return redirect()->back()->withErrors(['main' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
        /**
     * Mencari kuis berdasarkan kode unik dan mengembalikan datanya.
     */
    public function joinWithCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        // 1. Cari topik berdasarkan kode.
        //    'with' dan 'withCount' tetap penting agar accessor memiliki semua data yang dibutuhkan.
        $topic = Topic::where('code', $validated['code'])
                      ->with('subject')
                      ->withCount('questions')
                      ->first();

        // 2. Jika topik tidak ditemukan, kembalikan error.
        if (!$topic) {
            return response()->json(['error' => 'Quiz with that code was not found.'], 404);
        }

        // 3. Langsung panggil accessor 'modalData' dari model Topic.
        //    Laravel akan secara otomatis membuatkan URL gambar dan data lainnya untuk Anda.
        return response()->json($topic->modal_data);
    }       


    // ... (Metode lainnya tidak perlu diubah) ...
    private function getEditorViewData(Request $request, string $viewName)
    {
        $subjects = Subject::orderBy('subject_name')->get();
        $topic = null; 

        if ($request->has('topic_id')) {
            $topic = Topic::findOrFail($request->query('topic_id'));
            if ($topic->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
        }

        return view($viewName, [
            'subjects' => $subjects,
            'topic'    => $topic,
        ]);
    }

    public function addbutton(Request $request)
    {
        return $this->getEditorViewData($request, 'quiz.addbutton');
    }

    public function addcheckbox(Request $request)
    {
        return $this->getEditorViewData($request, 'quiz.addcheckbox');
    }

    public function addtypeanswer(Request $request)
    {
        return $this->getEditorViewData($request, 'quiz.addtypeanswer');
    }

    public function addreorder(Request $request)
    {
        return $this->getEditorViewData($request, 'quiz.addreorder');
    }
}
