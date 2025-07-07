@extends('layouts.quizeditor')

@section('title', 'Add Question')

@push('styles')
<style>
    .question-type-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 2px solid transparent;
        background-color: #F7B5A3;
    }
    .question-type-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 30px -10px rgba(238, 169, 157, 0.3);
    }
    
    .icon-box {
        width: 4rem; /* 64px */
        height: 4rem; /* 64px */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000000;
    }
    
    .icon-box .fa-square, .icon-box .fa-check-square {
        font-size: 2rem; /* 32px */
    }
    .icon-box .fa-layer-group {
        font-size: 1.8rem;
    }
    .icon-box .custom-icon-text {
        font-size: 1.8rem;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-6 py-16">
        
    <h1 class="text-center text-4xl font-extrabold text-gray-800 mb-12">ADD QUESTIONS</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <a href="{{ route('quiz.addbutton') }}">
            <!-- Tombol -->
            <div class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <i class="far fa-square"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">BUTTONS</h2>
                    <p class="text-gray-500">One correct answer</p>
                </div>
            </div>
        </a>
        <!-- Checkbox -->
        <a href="{{ route('quiz.addcheckbox') }}">
            <div class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <i class="far fa-check-square"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">CHECKBOXES</h2>
                    <p class="text-gray-500">Multiple correct answers</p>
                </div>
            </div>
        </a>
        <!-- Reorder -->
        <a href="{{ route('quiz.addreorder') }}">
            <div class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <i class="fas fa-layer-group"></i>
                 </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">REORDER</h2>
                    <p class="text-gray-500">Place answers in the correct order</p>
                </div>
            </div>
        </a>
        <!-- Type Answer -->
        <a href="{{ route('quiz.typeanswer') }}">
            <div class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <span class="custom-icon-text">Aa</span>
                 </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">TYPE ANSWER</h2>
                    <p class="text-gray-500">Type the correct answer</p>
                </div>
            </div>
        </a>
    </div>
    
</div>
@endsection
