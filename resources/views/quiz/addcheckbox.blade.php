@extends('layouts.quizeditor')

@section('title', 'Quiz Editor - Checkbox')

@push('styles')
<style>
    .answer-card {
        background-color: #FFE2D6;
        border: 3px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .custom-checkbox {
        width: 28px;
        height: 28px;
        border: 3px solid rgb(248, 132, 100);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .custom-checkbox i {
        display: none; 
    }
        .answer-card.selected {
        border-color:rgb(224, 126, 65);
        background-color:rgb(240, 207, 187);
    }
    .answer-card.selected .custom-checkbox {
        background-color: rgb(228, 167, 129);
        border-color: rgb(224, 126, 65);
    }
    .answer-card.selected .custom-checkbox i {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-8 py-8">
    <!-- Kotak Pertanyaan -->
    <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
        <textarea 
            id="question_text"
            rows="3" 
            placeholder="Type Question Here" 
            class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
        ></textarea>
    </div>

    <!-- Grid Opsi Jawaban (Selectable) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @for ($i = 0; $i < 4; $i++)
        <div class="answer-card p-8 rounded-2xl flex items-center justify-between shadow-md">
            <input type="text" placeholder="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
            <div class="custom-checkbox ml-4">
                <i class="fas fa-check text-white text-lg"></i>
            </div>
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

    let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
    let currentQuestion = pendingQuestions[questionIndex] || {};
    let correctChoiceIndices = currentQuestion.correct_choices || [];

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
        delete currentQuestion.correct_choice;

        const data = {
            ...currentQuestion, 
            q_type_name: 'Checkbox', 
            question_text: questionTextEl.value.trim(),
            choices: choiceInputs.map(input => input.value.trim()),
            correct_choices: correctChoiceIndices
        };
        pendingQuestions[questionIndex] = data;
        sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));
    };

    const updateCardStyles = () => {
        answerCards.forEach((card, index) => {
            if (correctChoiceIndices.includes(index)) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    };

    answerCards.forEach((card, index) => {
        card.addEventListener('click', () => {
            const pos = correctChoiceIndices.indexOf(index);
            if (pos === -1) {
                correctChoiceIndices.push(index);
            } else {
                correctChoiceIndices.splice(pos, 1);
            }
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
