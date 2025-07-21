<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz\Topic; 
use App\Models\Quiz\QuizAttempt;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rule; 

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $createdQuizzes = Topic::where('user_id', $user->id)
                        ->with('subject')
                        ->withCount('questions')
                        ->get();
        $quizHistory = QuizAttempt::where('user_id', $user->id)
                         ->with('topic.subject')
                         ->latest()
                         ->get();
        $subjectColors = [
            'Mathematics' => 'bg-yellow-100', 'English' => 'bg-pink-100', 'Chemistry' => 'bg-blue-100',
            'Computers' => 'bg-purple-100', 'Biology' => 'bg-green-100', 'Economy' => 'bg-gray-200',
            'Geography' => 'bg-teal-100', 'Physics' => 'bg-yellow-200', 'Music' => 'bg-indigo-100',
            'Sports' => 'bg-red-100', 'Mandarin' => 'bg-yellow-300',
        ];
        return view('profile.myprofile', compact('user', 'createdQuizzes', 'quizHistory', 'subjectColors'));
    }

    public function edit()
    {
        return view('profile.editprofile', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
    public function reviewAttempt(QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $attempt->load('topic.questions.choices', 'topic.questions.questionType');
        
        $userAnswersPayload = $attempt->user_answers;
        $results = [];

        foreach ($attempt->topic->questions as $question) {
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
                    $correctAnswer = $correctChoice->choice_text;
                    if ($userAnswerData) {
                        $isCorrect = (strcasecmp(trim($userAnswerData), trim($correctAnswer)) === 0);
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

            $results[] = [
                'question_text' => $question->question_text,
                'user_answer' => $userAnswerForDisplay,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect,
            ];
        }

        return view('profile.review', [
            'attempt' => $attempt,
            'results' => $results,
        ]);
    }
}
