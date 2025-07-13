@extends('layouts.app')

@section('title', 'Quiz - ' . $topic->topic_name)

@push('styles')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #FFFBF5;
        padding-bottom: 120px; /* Ruang untuk footer navigasi */
    }
    .brand-pink-light { background-color: #FDECE7; }
    .brand-pink-dark { background-color: #F5B9B0; }
    .brand-border { border-color: #F3EAE6; }
    [x-cloak] { display: none !important; }

    /* Styling untuk progress bar */
    .progress-bar-container {
        background-color: #F3EAE6;
        border-radius: 9999px;
        overflow: hidden;
    }
    .progress-bar {
        background-color: #4ade80; /* green-400 */
        transition: width 0.5s ease;
    }

    /* Styling untuk pilihan jawaban */
    .answer-choice {
        border: 3px solid #FDECE7;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .answer-choice:hover {
        border-color: #F5B9B0;
        transform: scale(1.02);
    }
    .answer-choice.selected {
        border-color: #EEA99D;
        background-color: #FDECE7;
        box-shadow: 0 4px 15px -5px rgba(238, 169, 157, 0.5);
    }
    .answer-choice.selected .answer-letter {
        background-color: #EEA99D;
        color: white;
    }

    .answer-letter {
        width: 40px;
        height: 40px;
        border: 2px solid #F5B9B0;
        transition: all 0.2s ease-in-out;
    }
</style>
@endpush

@section('content')
{{-- 
  Inisialisasi Alpine.js dengan data kuis dari controller.
  Variabel $quizData diharapkan berisi JSON dari semua pertanyaan dan pilihan.
--}}
<div x-data="quizTaker({{ json_encode($quizData) }})" x-init="startTimer()">
    
    <!-- ===== Header Kuis (Timer & Progress) ===== -->
    <div class="sticky top-0 bg-[#FFFBF5]/80 backdrop-blur-lg z-10 py-4 border-b brand-border">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold text-gray-700">
                    <i class="far fa-clock mr-2"></i>
                    <span x-text="formattedTime"></span>
                </div>
                <div class="w-1/2">
                    <div class="progress-bar-container w-full h-4">
                        <div class="progress-bar h-full" :style="`width: ${progressPercentage}%`"></div>
                    </div>
                </div>
                <div class="text-lg font-semibold text-gray-500">
                    <span x-text="currentQuestionIndex + 1"></span> / <span x-text="questions.length"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Konten Utama Kuis ===== -->
    <main class="container mx-auto px-6 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Template untuk setiap pertanyaan -->
            <template x-for="(question, index) in questions" :key="question.id">
                <div x-show="currentQuestionIndex === index" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-x-8">
                    
                    <!-- Teks Pertanyaan -->
                    <div class="bg-brand-pink-light p-8 rounded-2xl shadow-lg mb-10 text-center">
                        <p class="text-2xl font-bold text-gray-800" x-text="question.question_text"></p>
                    </div>

                    <!-- Pilihan Jawaban -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <template x-for="(choice, choiceIndex) in question.choices" :key="choice.id">
                            <div 
                                @click="selectAnswer(question.id, choice.id)"
                                class="answer-choice bg-white p-5 rounded-2xl flex items-center space-x-5"
                                :class="{ 'selected': userAnswers[question.id] === choice.id }"
                            >
                                <div class="answer-letter flex-shrink-0 rounded-lg flex items-center justify-center font-bold text-xl" x-text="String.fromCharCode(65 + choiceIndex)"></div>
                                <p class="text-lg font-semibold" x-text="choice.choice_text"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <!-- ===== Navigasi Bawah ===== -->
    <footer class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-sm p-4 border-t brand-border">
        <div class="container mx-auto flex items-center justify-between max-w-4xl">
            <button 
                @click="previousQuestion()" 
                :disabled="currentQuestionIndex === 0"
                class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-bold hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition"
            >
                <i class="fas fa-arrow-left mr-2"></i> Previous
            </button>
            
            {{-- Tombol Next akan berubah menjadi Submit di soal terakhir --}}
            <template x-if="currentQuestionIndex < questions.length - 1">
                <button 
                    @click="nextQuestion()"
                    class="btn-gradient text-white px-12 py-3 rounded-lg font-bold shadow-md"
                >
                    Next <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </template>

            {{-- Tombol Submit --}}
            <template x-if="currentQuestionIndex === questions.length - 1">
                <button 
                    @click="submitQuiz()"
                    class="bg-green-500 text-white px-12 py-3 rounded-lg font-bold shadow-md hover:bg-green-600 transition"
                >
                    Submit Quiz <i class="fas fa-check-circle ml-2"></i>
                </button>
            </template>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
<script>
    function quizTaker(quizData) {
        return {
            // Data dari Controller
            questions: quizData.questions || [],
            timeLimitInSeconds: (quizData.questions.length * 30), // Asumsi 30 detik per soal

            // State Kuis
            currentQuestionIndex: 0,
            userAnswers: {}, // Format: { questionId: choiceId }
            timeLeft: 0,
            timerInterval: null,

            // Inisialisasi
            init() {
                this.timeLeft = this.timeLimitInSeconds;
            },

            // Properti turunan (computed properties)
            get progressPercentage() {
                return ((this.currentQuestionIndex + 1) / this.questions.length) * 100;
            },
            get formattedTime() {
                const minutes = Math.floor(this.timeLeft / 60);
                const seconds = this.timeLeft % 60;
                return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            },

            // Aksi/Metode
            startTimer() {
                this.timerInterval = setInterval(() => {
                    if (this.timeLeft > 0) {
                        this.timeLeft--;
                    } else {
                        clearInterval(this.timerInterval);
                        this.submitQuiz(); // Otomatis submit jika waktu habis
                    }
                }, 1000);
            },
            selectAnswer(questionId, choiceId) {
                this.userAnswers[questionId] = choiceId;
            },
            nextQuestion() {
                if (this.currentQuestionIndex < this.questions.length - 1) {
                    this.currentQuestionIndex++;
                }
            },
            previousQuestion() {
                if (this.currentQuestionIndex > 0) {
                    this.currentQuestionIndex--;
                }
            },
            submitQuiz() {
                clearInterval(this.timerInterval);
                alert('Quiz Submitted!');
                console.log('Final Answers:', this.userAnswers);
                // Di sini Anda akan mengirim 'this.userAnswers' ke backend Laravel
                // menggunakan form submission atau Fetch API.
                // Contoh: window.location.href = '/quiz/submit';
            }
        }
    }
</script>
@endpush
