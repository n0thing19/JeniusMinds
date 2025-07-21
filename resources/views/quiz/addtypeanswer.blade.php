@extends('layouts.quizeditor')

@section('title', 'Quiz Editor - Type Answer')

@section('content')
<div class="container mx-auto px-8 py-8">
    <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
        <textarea 
            id="question_text"
            rows="3" 
            placeholder="Type Question Here" 
            class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
        ></textarea>
    </div>

    <!-- Kotak Jawaban -->
    <div class="bg-white p-12 rounded-xl shadow-lg brand-border border-2">
        <textarea 
            id="answer_text"
            rows="5" 
            placeholder="Type Answer Here" 
            class="w-full bg-transparent text-xl font-semibold placeholder:text-gray-500/80 focus:outline-none resize-none"
        ></textarea>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const storageKey = 'pendingQuestions';
    const questionTextEl = document.getElementById('question_text');
    const answerTextEl = document.getElementById('answer_text');
    const urlParams = new URLSearchParams(window.location.search);
    const questionIndex = parseInt(urlParams.get('edit'), 10);

    let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
    let currentQuestion = pendingQuestions[questionIndex] || {};

    const loadQuestionData = () => {
        questionTextEl.value = currentQuestion.question_text || '';
        answerTextEl.value = currentQuestion.choices ? (currentQuestion.choices[0] || '') : '';
    };

    const saveQuestionData = () => {
        delete currentQuestion.correct_choice;
        delete currentQuestion.correct_choices;

        const data = {
            ...currentQuestion,
            q_type_name: 'TypeAnswer',
            question_text: questionTextEl.value.trim(),
            choices: [answerTextEl.value.trim(), '', '', ''] 
        };
        
        pendingQuestions[questionIndex] = data;
        sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));
    };

    questionTextEl.addEventListener('input', saveQuestionData);
    answerTextEl.addEventListener('input', saveQuestionData);

    loadQuestionData();
});
</script>
@endpush
