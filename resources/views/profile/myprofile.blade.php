@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
{{-- Style Anda yang sudah ada, tidak ada perubahan --}}
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
{{-- 1. Tambahkan x-data di sini untuk menginisiasi Alpine.js --}}
<main class="container mx-auto px-6 py-12" x-data="profilePage()">
    
    <section class="flex items-center space-x-6 pb-8 border-b-2 brand-border">
        <div class="profile-icon-container">
            <span class="text-4xl font-bold text-pink-400">{{ substr($user->name, 0, 1) }}</span>
        </div>
        <div class="flex items-center space-x-4">
            <h1 class="text-4xl font-bold text-gray-800">{{ $user->name }}</h1>
            <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-gray-800 transition-colors">
                <i class="fas fa-pencil-alt text-2xl"></i>
            </a>
        </div>
    </section>

    <section class="mt-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">My Quiz</h2>

        {{-- 2. Tambahkan blok notifikasi untuk pesan sukses atau error --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        {{-- 3. Ganti nama variabel $quizzes menjadi $topics agar sesuai controller --}}
        @if($topics->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-2xl">
                <p class="text-gray-500">You haven't created any quizzes yet.</p>
                <a href="{{ route('quiz.editor') }}" class="mt-4 inline-block bg-[#F5B9B0] text-black px-6 py-2 rounded-lg font-bold shadow-sm hover:brightness-105 transition">Create Your First Quiz</a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach($topics as $topic)
                    {{-- 4. Ubah <a> menjadi <button> yang memicu modal --}}
                    <button 
                        type="button"
                        @click="openModal({{ $topic->topic_id }}, '{{ e($topic->topic_name) }}')"
                        class="quiz-card p-6 rounded-2xl text-left {{ $subjectColors[$topic->subject->subject_name] ?? 'bg-gray-100' }}"
                    >
                        <h3 class="font-bold text-xl text-gray-800 mb-4">{{ $topic->topic_name }}</h3>
                        <p class="text-sm text-gray-500">
                           {{ $topic->questions_count }} {{ Str::plural('Question', $topic->questions_count) }}
                        </p>
                    </button>
                @endforeach
            </div>
        @endif
    </section>

    <div 
        x-show="isModalOpen" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        x-cloak
    >
        <div 
            @click.away="isModalOpen = false" 
            class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 m-4"
            x-show="isModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Manage Quiz</h3>
            <p class="text-gray-600 mb-6">What would you like to do with the quiz "<span x-text="selectedTopicName" class="font-semibold"></span>"?</p>
            
            <div class="flex flex-col space-y-4">
                {{-- Tombol Edit mengarah ke URL editor dengan topic_id --}}
                <a :href="`{{ url('/quiz/editor') }}/${selectedTopicId}`" class="w-full text-center bg-[#FDECE7] text-gray-800 font-bold py-3 px-5 rounded-lg hover:brightness-105 transition-colors duration-200">
                    <i class="fas fa-pencil-alt mr-2"></i> Edit Quiz
                </a>

                {{-- Tombol Delete memicu fungsi konfirmasi --}}
                <button @click="confirmDelete()" type="button" class="w-full bg-red-600 text-white font-bold py-3 px-5 rounded-lg hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-trash-alt mr-2"></i> Delete Quiz
                </button>
            </div>
            
            <div class="mt-6 text-center">
                <button @click="isModalOpen = false" class="text-gray-500 hover:text-gray-700 font-semibold">Cancel</button>
            </div>
        </div>
    </div>

    {{-- Form tersembunyi yang digunakan oleh JavaScript untuk mengirim request DELETE --}}
    <form id="delete-quiz-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    
</main>
@endsection

@push('scripts')
{{-- 5. Tambahkan logika Alpine.js untuk mengelola modal --}}
<script>
    function profilePage() {
        return {
            isModalOpen: false,
            selectedTopicId: null,
            selectedTopicName: '',

            openModal(topicId, topicName) {
                this.selectedTopicId = topicId;
                this.selectedTopicName = topicName;
                this.isModalOpen = true;
            },

            confirmDelete() {
                if (confirm(`Are you absolutely sure you want to delete the quiz "${this.selectedTopicName}"? This action cannot be undone.`)) {
                    const form = document.getElementById('delete-quiz-form');
                    // Atur action form secara dinamis berdasarkan ID topik yang dipilih
                    form.action = `{{ url('/quiz/topic') }}/${this.selectedTopicId}`;
                    form.submit();
                }
            }
        }
    }
</script>
@endpush