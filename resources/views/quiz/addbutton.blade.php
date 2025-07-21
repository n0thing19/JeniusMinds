@extends('layouts.quizeditor')

@section('title', 'Quiz Editor - Buttons')

@push('styles')
<style>
    .answer-card {
        background-color: #FFE2D6;
        border: 5px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .answer-card.correct {
        border-color: #45862E;
        box-shadow: 0 0 20px rgba(47, 201, 145, 0.3);
    }
    .answer-card.incorrect {
        border-color: #A62828;
        box-shadow: 0 0 20px rgba(231, 69, 69, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-8 py-8">
    <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
        <textarea id="question_text" rows="3" placeholder="Type Question Here" class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"></textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8" id="choices-grid">
        @for ($i = 0; $i < 4; $i++)
        <div class="answer-card p-8 rounded-2xl flex items-center shadow-md">
            <input type="text" placeholder="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none">
        </div>
        @endfor
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const storageKey = 'pendingQuestions';
    const questionTextEl = document.getElementById('question_text');
    const answerCards = Array.from(document.querySelectorAll('.answer-card'));
    const choiceInputs = answerCards.map(card => card.querySelector('input[type="text"]'));

    const urlParams = new URLSearchParams(window.location.search);
    const questionIndex = parseInt(urlParams.get('edit'), 10);

    // Hapus baris yang mengatur 'currentQuestionIndex', karena layout sudah menanganinya.
    
    let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
    let currentQuestion = pendingQuestions[questionIndex] || {};
    let correctChoiceIndex = currentQuestion.correct_choice ?? -1;

    const loadQuestionData = () => {
        questionTextEl.value = currentQuestion.question_text || '';
        if (currentQuestion.choices) {
            choiceInputs.forEach((input, i) => {
                input.value = currentQuestion.choices[i] || '';
            });
        }
        updateCardStyles();
    };

    const saveQuestionData = () => {
        const data = {
            q_type_name: 'Button', 
            question_text: questionTextEl.value.trim(),
            choices: choiceInputs.map(input => input.value.trim()),
            correct_choice: correctChoiceIndex
        };
        pendingQuestions[questionIndex] = data;
        sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));
    };

    const updateCardStyles = () => {
        answerCards.forEach((card, index) => {
            card.classList.remove('correct', 'incorrect');
            if (correctChoiceIndex !== -1) {
                if (index === correctChoiceIndex) {
                    card.classList.add('correct');
                } else {
                    card.classList.add('incorrect');
                }
            }
        });
    };

    answerCards.forEach((card, index) => {
        card.addEventListener('click', () => {
            correctChoiceIndex = index;
            updateCardStyles();
            saveQuestionData(); 
        });
    });

    questionTextEl.addEventListener('input', saveQuestionData);
    choiceInputs.forEach(input => input.addEventListener('input', saveQuestionData));

    loadQuestionData();
});
</script>
@endpush
