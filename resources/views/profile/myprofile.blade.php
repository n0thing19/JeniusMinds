@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-icon-container {
        width: 80px;
        height: 80px;
        background-color: #FDECE7;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .quiz-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .quiz-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.07);
    }
</style>
@endpush

@section('content')
<main class="container mx-auto px-6 py-12">
    
    <!-- Bagian Profil -->
    <section class="flex items-center space-x-6 pb-8 border-b-2 brand-border">
        <div class="profile-icon-container">
            {{-- Menampilkan inisial nama pengguna sebagai placeholder --}}
            <span class="text-4xl font-bold text-pink-400">{{ substr($user->name, 0, 1) }}</span>
        </div>
        <div class="flex items-center space-x-4">
            {{-- Menampilkan nama pengguna secara dinamis --}}
            <h1 class="text-4xl font-bold text-gray-800">{{ $user->name }}</h1>
            <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-gray-800 transition-colors">
                <i class="fas fa-pencil-alt text-2xl"></i>
            </a>
        </div>
    </section>

    <!-- Bagian "My Quiz" -->
    <section class="mt-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">My Quiz</h2>

        @if($quizzes->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-2xl">
                <p class="text-gray-500">You haven't created any quizzes yet.</p>
                <a href="{{ route('quiz.editor') }}" class="mt-4 inline-block bg-[#F5B9B0] text-black px-6 py-2 rounded-lg font-bold shadow-sm hover:brightness-105 transition">Create Your First Quiz</a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                {{-- Loop untuk setiap kuis yang dimiliki pengguna --}}
                @foreach($quizzes as $quiz)
                    <a href="{{ route('quiz.editor', $quiz->id) }}" class="quiz-card p-6 rounded-2xl {{ $subjectColors[$quiz->subject->subject_name] ?? 'bg-gray-100' }}">
                        <h3 class="font-bold text-xl text-gray-800 mb-4">{{ $quiz->topic_name }}</h3>
                        {{-- Menampilkan tanggal pembuatan secara dinamis --}}
                        {{-- <p class="text-sm text-gray-500">Created : {{ $quiz->created_at->format('d M Y') }}</p> --}}
                    </a>
                @endforeach
            </div>
        @endif
    </section>
    
</main>
@endsection
