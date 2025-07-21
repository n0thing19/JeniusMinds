@extends('layouts.quizeditor')

@section('title', 'Quiz Editor - Choose Type')

@push('styles')
<style>
    .editor-bg {
        background-color: #FFFAF3;
        background-image: radial-gradient(#F3EAE6 1px, transparent 1px);
        background-size: 16px 16px;
    }

    .question-type-card {
        border: 1px solid transparent;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); 
        position: relative;
        overflow: hidden;
        background-image: linear-gradient(to right, #F7B5A3, #E99A87); 
        color: black;
    }

    .question-type-card:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 25px -8px rgba(238, 169, 157, 0.6); 
        filter: brightness(1.1); 
    }

    .icon-box {
        width: 4.5rem;
        height: 4.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        transition: all 0.3s ease;
    }

    .question-type-card:hover .icon-box {
        transform: scale(1.1) rotate(-10deg);
    }

    .icon-box .fa-square, .icon-box .fa-check-square, .icon-box .fa-layer-group {
        font-size: 2.5rem;
    }
    .icon-box .custom-icon-text {
        font-size: 2.5rem;
        font-weight: 800;
    }
</style>
@endpush

@section('content')
<div class="editor-bg">
    <div class="container mx-auto px-6  pt-12">
        {{-- Blok Pesan Error/Sukses --}}
        <div class="mb-8 max-w-4xl mx-auto">
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p class="font-bold">Could not save the quiz:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
            @endif
        </div>

        {{-- Konten Pemilihan Tipe Soal --}}
        <h1 class="text-center text-4xl font-extrabold text-gray-800 mb-4">Choose Question Type</h1>        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <a href="#" id="add-button-type" class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <i class="far fa-square"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">BUTTONS</h2>
                    <p class="text-gray-500">One correct answer</p>
                </div>
            </a>
            <a href="#" id="add-checkbox-type" class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <i class="far fa-check-square"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">CHECKBOXES</h2>
                    <p class="text-gray-500">Multiple correct answers</p>
                </div>
            </a>
            <a href="#" id='add-reorder-type' class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">REORDER</h2>
                    <p class="text-gray-500">Place answers in the correct order</p>
                </div>
            </a>
            <a href="#" id="add-type-answer" class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <span class="custom-icon-text">Aa</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">TYPE ANSWER</h2>
                    <p class="text-gray-500">Type the correct answer</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const storageKey = 'pendingQuestions';

    @isset($existingQuestions)
        const existingSessionData = sessionStorage.getItem(storageKey);
        if (!existingSessionData || JSON.parse(existingSessionData).length === 0) {
            sessionStorage.setItem(storageKey, @json($existingQuestions));
        }
    @endisset

    function prepareNewQuestion(questionTypeName, routeName) {
        let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
        const newIndex = pendingQuestions.length;

        const newQuestionData = {
            q_type_name: questionTypeName,
            question_text: '',
            choices: ['', '', '', ''],
            correct_choice: -1
        };
        
        pendingQuestions.push(newQuestionData);
        sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));
        
        setTimeout(() => {
            let newUrl = `{{ url('/quiz') }}/${routeName}?edit=${newIndex}`;

            if (window.quizConfig && window.quizConfig.topicId) {
                newUrl += `&topic_id=${window.quizConfig.topicId}`;
            }
            
            window.location.href = newUrl;
        }, 100); 
    }

    document.getElementById('add-button-type').addEventListener('click', function(e) {
        e.preventDefault();
        prepareNewQuestion('Button', 'addbutton');
    });

    document.getElementById('add-checkbox-type').addEventListener('click', function(e) {
        e.preventDefault();
        prepareNewQuestion('Checkbox', 'addcheckbox');
    });
    
    document.getElementById('add-reorder-type').addEventListener('click', function(e) {
        e.preventDefault();
        prepareNewQuestion('Reorder', 'addreorder');
    });

    document.getElementById('add-type-answer').addEventListener('click', function(e) {
        e.preventDefault();
        prepareNewQuestion('TypeAnswer', 'addtypeanswer');
    });
});
</script>
@endpush
