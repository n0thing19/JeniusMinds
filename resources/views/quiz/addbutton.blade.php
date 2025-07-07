@extends('layouts.quizeditor')

@section('title', 'Quiz Editor - Buttons')

@push('styles')
<style>
    .answer-card {
        background-color: #FFE2D6;
        border: 5px solid transparent;
        transition: all 0.3s ease;
    }
    .answer-card.correct {
        border-color: #45862E; /* Green-400 */
        box-shadow: 0 0 20px rgba(47, 201, 145, 0.3);
    }
    .answer-card.incorrect {
        border-color: #A62828; /* Red-500 */
        box-shadow: 0 0 20px rgba(231, 69, 69, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-8 py-8">
    
    <!-- Kotak Pertanyaan -->
    <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
        <textarea 
            rows="3" 
            placeholder="Type Question Here" 
            class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
        ></textarea>
    </div>

    <!-- Grid Opsi Jawaban -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Jawaban Benar -->
        <div class="answer-card correct p-8 rounded-2xl flex items-center shadow-md">
            <input 
                type="text" 
                placeholder="Type Answer Here" 
                class="w-full bg-transparent text-lg font-semibold focus:outline-none"
            >
        </div>
        <!-- Jawaban Salah -->
        <div class="answer-card incorrect p-8 rounded-2xl flex items-center shadow-md">
            <input 
                type="text" 
                placeholder="Type Answer Here" 
                class="w-full bg-transparent text-lg font-semibold focus:outline-none"
            >
        </div>
        <!-- Jawaban Salah -->
        <div class="answer-card incorrect p-8 rounded-2xl flex items-center shadow-md">
            <input 
                type="text" 
                placeholder="Type Answer Here" 
                class="w-full bg-transparent text-lg font-semibold focus:outline-none"
            >
        </div>
        <!-- Jawaban Salah -->
        <div class="answer-card incorrect p-8 rounded-2xl flex items-center shadow-md">
            <input 
                type="text" 
                placeholder="Type Answer Here" 
                class="w-full bg-transparent text-lg font-semibold focus:outline-none"
            >
        </div>
    </div>
    
</div>
@endsection
