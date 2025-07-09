@extends('layouts.app')

@section('title', 'User Profile - Jenius Minds')

@push('styles')
<style>
    body {
        background-color: #FFFAF3;
    }
    .brand-pink-dark-bg { background-color: #F5B9B0; }
    
    .profile-icon-container {
        width: 80px;
        height: 80px;
        background-color: #FDECE7; /* brand-pink-light */
        border-radius: 0.75rem; /* rounded-xl */
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
            {{-- Ganti dengan gambar profil pengguna jika ada, jika tidak gunakan placeholder --}}
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <div class="flex items-center space-x-4">
            {{-- Ganti dengan nama pengguna yang sebenarnya --}}
            <h1 class="text-4xl font-bold text-gray-800">Misellin Mindany</h1>
            <a href="{{ route('editprofile') }}" class="text-gray-500 hover:text-gray-800 transition-colors">
                <i class="fas fa-pencil-alt text-2xl"></i>
            </a>
        </div>
    </section>

    <!-- Bagian "My Quiz" -->
    <section class="mt-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">My Quiz</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            {{-- Loop melalui kuis milik pengguna --}}
            <!-- Kartu Kuis 1 -->
            <div class="quiz-card bg-[#F5F5F5] p-8 rounded-2xl">
                <h3 class="font-bold text-xl text-gray-800 mb-4">Mathematics</h3>
                <p class="text-sm text-gray-500">Created : 23 Jun 2025</p>
            </div>

            <!-- Kartu Kuis 2 -->
            <div class="quiz-card bg-[#DEDDE8] p-8 rounded-2xl">
                <h3 class="font-bold text-xl text-gray-800 mb-4">Computers</h3>
                <p class="text-sm text-gray-500">Created : 21 Jun 2025</p>
            </div>

        </div>
    </section>
    
</main>
@endsection
