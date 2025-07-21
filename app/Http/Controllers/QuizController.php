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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 
use App\Models\Quiz\QuizAttempt;


class QuizController extends Controller
{
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
            $results = [];

            foreach ($topic->questions as $question) {
                $questionId = $question->question_id;
                $userAnswerData = $userAnswersPayload[$questionId] ?? null;
                $isCorrect = false;
                $correctAnswer = null;
                $userAnswerForDisplay = 'Not Answered';

                switch ($question->questionType->type_name) {
                    case 'Button':
                        $correctChoice = $question->choices->where('is_correct', true)->first();
                        if ($correctChoice) {
                            $correctAnswer = $correctChoice->choice_text;
                            $isCorrect = ((int)$userAnswerData === (int)$correctChoice->choice_id);
                        }
                        if ($userAnswerData) {
                            $userChoice = $question->choices->find($userAnswerData);
                            $userAnswerForDisplay = $userChoice ? $userChoice->choice_text : 'Invalid Answer';
                        }
                        break;

                    case 'TypeAnswer':
                        $correctChoice = $question->choices->where('is_correct', true)->first();
                        if ($correctChoice) {
                            $correctAnswer = $correctChoice->choice_text;
                            if ($userAnswerData) {
                                $isCorrect = (strcasecmp(trim($userAnswerData), trim($correctAnswer)) === 0);
                            }
                        }
                        if ($userAnswerData) {
                            $userAnswerForDisplay = $userAnswerData;
                        }
                        break;

                    case 'Checkbox':
                        $correctChoices = $question->choices->where('is_correct', true);
                        $correctAnswer = $correctChoices->pluck('choice_text')->all();
                        if (is_array($userAnswerData)) {
                            $correctChoiceIds = $correctChoices->pluck('choice_id')->sort()->values()->all();
                            $userAnswerIds = collect($userAnswerData)->map(fn($id) => (int)$id)->sort()->values()->all();
                            $isCorrect = ($userAnswerIds == $correctChoiceIds);
                            $userAnswerForDisplay = $question->choices->whereIn('choice_id', $userAnswerData)->pluck('choice_text')->all();
                        }
                        break;
                    
                    case 'Reorder':
                        $correctlyOrderedChoices = $question->choices->sortBy('correct_order');
                        $correctAnswer = $correctlyOrderedChoices->pluck('choice_text')->all();
                        if (is_array($userAnswerData) && !empty($userAnswerData)) {
                            $userOrderedIds = collect($userAnswerData)->sortBy('order')->pluck('choice_id');
                            $isCorrect = $correctlyOrderedChoices->pluck('choice_id')->values()->all() == $userOrderedIds->values()->all();
                            $userAnswerForDisplay = $question->choices->whereIn('choice_id', $userOrderedIds)->sortBy(function($choice) use ($userOrderedIds) {
                                return array_search($choice->choice_id, $userOrderedIds->all());
                            })->pluck('choice_text')->all();
                        }
                        break;
                }

                if ($isCorrect) {
                    $score++;
                }

                $results[] = [
                    'question_text' => $question->question_text,
                    'user_answer' => $userAnswerForDisplay,
                    'correct_answer' => $correctAnswer,
                    'is_correct' => $isCorrect,
                ];
            }
            
            $attempt = new QuizAttempt();
            $attempt->user_id = Auth::id();
            $attempt->topic_id = $topicId;
            $attempt->score = $score;
            $attempt->total_questions = $topic->questions->count();
            $attempt->user_answers = $userAnswersPayload;
            $attempt->save();

            return response()->json([
                'message' => 'Quiz submitted successfully!',
                'score' => $score,
                'total_questions' => $topic->questions->count(),
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Quiz Submission Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return response()->json([
                'message' => 'An error occurred on the server while processing the quiz.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
            do {
                $code = Str::random(6);
            } while (Topic::where('code', $code)->exists());

            $newTopic = Topic::create([
                'topic_name' => $validatedData['new_topic_name'],
                'subject_id' => $validatedData['subject_id'],
                'user_id' => Auth::id(),
                'code' => $code, 
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
    public function joinWithCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $topic = Topic::where('code', $validated['code'])
                      ->with('subject')
                      ->withCount('questions')
                      ->first();

        if (!$topic) {
            return response()->json(['error' => 'Quiz with that code was not found.'], 404);
        }

        return response()->json($topic->modal_data);
    }       

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
