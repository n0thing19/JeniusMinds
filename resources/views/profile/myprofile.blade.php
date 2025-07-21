@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-icon-container { width: 80px; height: 80px; background-color: #FDECE7; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; }
    .quiz-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; justify-content: space-between; }
    .quiz-card:hover { transform: translateY(-8px); box-shadow: 0 15px 25px -10px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
    .question-count-badge { background-color: rgba(255, 255, 255, 0.6); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); border: 1px solid rgba(255, 255, 255, 0.7); }
    .btn-gradient { background-image: linear-gradient(to right, #F7B5A3, #E99A87); transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); }
    .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 8px 15px -8px rgba(238, 169, 157, 0.6); filter: brightness(1.05); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<main class="container mx-auto px-6 py-12" x-data="profilePage()">
    
    <section class="flex items-center space-x-6">
        <div class="profile-icon-container">
            <span class="text-4xl font-bold text-pink-400">{{ substr($user->name, 0, 1) }}</span>
        </div>
        <div>
            <div class="flex items-center space-x-4">
                <h1 class="text-4xl font-bold text-gray-800">{{ $user->name }}</h1>
                <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-gray-800 transition-colors">
                    <i class="fas fa-pencil-alt text-2xl"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ================= TOMBOL NAVIGASI BARU ================= -->
    <div class="mt-8 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'myQuizzes'"
                    :class="{ 'border-pink-500 text-pink-600': activeTab === 'myQuizzes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'myQuizzes' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg transition-colors focus:outline-none">
                My Quizzes
            </button>
            <button @click="activeTab = 'quizHistory'"
                    :class="{ 'border-pink-500 text-pink-600': activeTab === 'quizHistory', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'quizHistory' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg transition-colors focus:outline-none">
                Quiz History
            </button>
        </nav>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 my-6 rounded-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- ================= BAGIAN 1: MY QUIZZES (KONTEN TAB) ================= -->
    <div x-show="activeTab === 'myQuizzes'" x-cloak>
        <section class="mt-10">
            @if($createdQuizzes->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-2xl">
                    <p class="text-gray-500">You haven't created any quizzes yet.</p>
                    <a href="{{ route('quiz.editor') }}" class="mt-4 inline-block bg-[#F5B9B0] text-black px-6 py-2 rounded-lg font-bold shadow-sm hover:brightness-105 transition">Create Your First Quiz</a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($createdQuizzes as $topic)
                        <button
                            type="button"
                            @click="openModal({{ $topic->topic_id }}, '{{ e($topic->topic_name) }}', '{{ $topic->code }}', '{{ e($topic->subject->subject_name) }}')"
                            class="quiz-card p-5 rounded-2xl text-left shadow-lg {{ $subjectColors[$topic->subject->subject_name] ?? 'bg-gray-100' }}">
                            <div>
                                <p class="text-sm font-semibold opacity-80" style="color: #4a5568;">{{ $topic->subject->subject_name }}</p>
                                <h3 class="font-extrabold text-xl text-gray-800 mt-2 line-clamp-3">{{ $topic->topic_name }}</h3>
                            </div>
                            <div class="mt-5 text-right">
                                <span class="question-count-badge text-gray-800 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $topic->questions_count }} {{ Str::plural('Question', $topic->questions_count) }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <!-- ================= BAGIAN 2: QUIZ HISTORY (KONTEN TAB) ================= -->
    <div x-show="activeTab === 'quizHistory'" x-cloak>
        <section class="mt-10">
            @if($quizHistory->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-2xl">
                    <p class="text-gray-500">You haven't taken any quizzes yet.</p>
                    <a href="{{ route('homepage') }}" class="mt-4 inline-block bg-[#F5B9B0] text-black px-6 py-2 rounded-lg font-bold shadow-sm hover:brightness-105 transition">Find a Quiz to Take</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($quizHistory as $attempt)
                        <div class="bg-white p-4 rounded-xl shadow-sm border flex items-center justify-between hover:shadow-md transition-shadow">
                            <div class="flex-grow">
                                <p class="font-bold text-lg text-gray-800">{{ $attempt->topic->topic_name }}</p>
                                <p class="text-sm text-gray-500">{{ $attempt->topic->subject->subject_name }}</p>
                            </div>
                            <div class="text-center mx-4">
                                 <p class="text-2xl font-bold text-green-600">{{ $attempt->score }}/<span class="text-gray-500">{{ $attempt->total_questions }}</span></p>
                                 <p class="text-xs text-gray-400">Score</p>
                            </div>
                            <div class="text-right text-sm text-gray-500 mr-6">
                                <p>{{ $attempt->created_at->format('d M Y') }}</p>
                                <p>{{ $attempt->created_at->format('h:i A') }}</p>
                            </div>
                            <a href="{{ route('profile.review_attempt', $attempt->id) }}" class="btn-gradient text-black font-bold py-2 px-5 rounded-full shadow-sm whitespace-nowrap">View Review</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <!-- ================= MODAL UNTUK "MY QUIZZES" ================= -->
    <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
        <div @click.away="isModalOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 m-4" x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Manage Quiz</h3>
            <p class="text-gray-600 mb-2">What would you like to do with the quiz "<span x-text="selectedTopicName" class="font-semibold"></span>"?</p>
            
            <div class="my-6 text-center bg-gray-50 p-4 rounded-lg border">
                <p class="text-sm text-gray-600 font-medium">Share this quiz with unique Code:</p>
                <div class="mt-2 inline-flex items-center gap-x-3">
                    <span x-text="selectedTopicCode" class="text-2xl font-mono font-bold tracking-widest text-gray-800 bg-gray-200 px-4 py-1 rounded-md"></span>
                    <button type="button" @click="copyCodeToClipboard()" title="Salin kode" class="p-2 text-gray-500 hover:bg-gray-200 hover:text-gray-900 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                </div>
            </div>

            <div class="flex flex-col space-y-4">
                <a :href="`{{ url('/quiz/editor') }}/${selectedTopicId}`" class="w-full text-center bg-[#FDECE7] text-gray-800 font-bold py-3 px-5 rounded-lg hover:brightness-105 transition-colors duration-200">
                    <i class="fas fa-pencil-alt mr-2"></i> Edit Quiz
                </a>
                <button @click="confirmDelete()" type="button" class="w-full bg-red-600 text-white font-bold py-3 px-5 rounded-lg hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-trash-alt mr-2"></i> Delete Quiz
                </button>
            </div>
            
            <div class="mt-6 text-center">
                <button @click="isModalOpen = false" class="text-gray-500 hover:text-gray-700 font-semibold">Cancel</button>
            </div>
        </div>
    </div>

    <form id="delete-quiz-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</main>
@endsection

@push('scripts')
<script>
    function profilePage() {
        return {
            activeTab: 'myQuizzes', 
            isModalOpen: false,
            selectedTopicId: null,
            selectedTopicName: '',
            selectedTopicCode: '',
            selectedSubjectName: '',
            openModal(topicId, topicName, topicCode, subjectName) {
                this.selectedTopicId = topicId;
                this.selectedTopicName = topicName;
                this.selectedTopicCode = topicCode;
                this.selectedSubjectName = subjectName;
                this.isModalOpen = true;
            },
            copyCodeToClipboard() {
                const textToCopy = `Ayo kerjakan kuis seru tentang "${this.selectedTopicName}" (${this.selectedSubjectName}) di Jenius Minds!\n\nMasukkan kode ini untuk bergabung: ${this.selectedTopicCode}`;
                const el = document.createElement('textarea');
                el.value = textToCopy;
                el.setAttribute('readonly', '');
                el.style.position = 'absolute';
                el.style.left = '-9999px';
                document.body.appendChild(el);
                el.select();
                try {
                    document.execCommand('copy');
                    alert('Pesan undangan dan kode kuis berhasil disalin!');
                } catch (err) {
                    alert('Oops, gagal menyalin kode.');
                }
                document.body.removeChild(el);
            },
            confirmDelete() {
                if (confirm(`Are you absolutely sure you want to delete the quiz "${this.selectedTopicName}"? This action cannot be undone.`)) {
                    const form = document.getElementById('delete-quiz-form');
                    form.action = `{{ url('/quiz/topic') }}/${this.selectedTopicId}`;
                    form.submit();
                }
            }
        }
    }
</script>
@endpush
