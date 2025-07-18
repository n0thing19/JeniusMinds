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

    /* Style untuk tombol gradient di footer */
    .btn-gradient {
        background-image: linear-gradient(to right, #F7B5A3, #E99A87);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px -8px rgba(238, 169, 157, 0.6);
        filter: brightness(1.05);
    }
</style>
@endpush

@section('content')
<div x-data="quizTaker({{ json_encode($quizData) }})" x-init="init(); startTimer()" x-cloak>
    
    <div x-show="!isFinished">
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

        <main class="container mx-auto px-6 py-8">
            <div class="max-w-4xl mx-auto">
                <template x-for="(question, index) in questions" :key="question.id">
                    <div x-show="currentQuestionIndex === index" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-x-8">
                        
                        <div class="bg-[#FFE2D6] p-12 rounded-3xl shadow-lg mb-10 text-center">
                            <p class="text-2xl font-bold text-gray-800" x-text="question.question_text"></p>
                        </div>

                        <div>
                            <template x-if="question.q_type_name === 'Button'"><div class="grid grid-cols-1 md:grid-cols-2 gap-6">@include('quiz.partials._button')</div></template>
                            <template x-if="question.q_type_name === 'Checkbox'"><div class="grid grid-cols-1 md:grid-cols-2 gap-6">@include('quiz.partials._checkbox')</div></template>
                            <template x-if="question.q_type_name === 'TypeAnswer'">@include('quiz.partials._typeanswer')</template>
                            <template x-if="question.q_type_name === 'Reorder'"><div class="space-y-4">@include('quiz.partials._reorder')</div></template>
                        </div>
                    </div>
                </template>
            </div>
        </main>

        <footer class="fixed bottom-0 left-0 right-0 bg-[#FFFAF3] backdrop-blur-sm p-4 border-t brand-border">
            <div class="container mx-auto flex items-center justify-between max-w-4xl">
                <button @click="previousQuestion()" :disabled="currentQuestionIndex === 0" class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-bold hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition"><i class="fas fa-arrow-left mr-2"></i> Previous</button>
                <template x-if="currentQuestionIndex < questions.length - 1"><button @click="nextQuestion()" class="btn-gradient text-white px-12 py-3 rounded-lg font-bold shadow-md">Next <i class="fas fa-arrow-right ml-2"></i></button></template>
                <template x-if="currentQuestionIndex === questions.length - 1"><button @click="submitQuiz()" class="bg-green-500 text-white px-12 py-3 rounded-lg font-bold shadow-md hover:bg-green-600 transition">Submit Quiz <i class="fas fa-check-circle ml-2"></i></button></template>
            </div>
        </footer>
    </div>

    <div x-show="isFinished" x-transition.opacity class="container mx-auto max-w-2xl text-center bg-white p-10 rounded-2xl shadow-xl mt-10">
        <h2 class="text-4xl font-extrabold text-gray-800">Quiz Completed!</h2>
        <p class="text-lg text-gray-600 mt-2">Here is your final score.</p>
        
        <div class="my-8">
            <p class="text-6xl font-bold text-green-500">
                <span x-text="finalScore">...</span> / <span x-text="questions.length"></span>
            </p>
            <p class="text-xl font-semibold mt-2">Correct Answers</p>
        </div>

        <div class="mb-8">
            <p class="text-gray-500">Time taken to complete the quiz:</p>
            <p class="text-2xl font-bold text-gray-800">
                <span x-text="timeTaken.minutes">0</span> minutes <span x-text="timeTaken.seconds">0</span> seconds
            </p>
        </div>

        <a href="{{ route('homepage.index') }}" class="btn-gradient text-white font-bold py-4 px-12 rounded-full shadow-lg text-lg">Back to Home</a>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function quizTaker(quizData) {
        return {
            // Data & State
            questions: quizData.questions || [],
            timeLimitInSeconds: (quizData.questions.length * 30),
            currentQuestionIndex: 0,
            userAnswers: {},
            timeLeft: 0,
            timerInterval: null,
            isFinished: false,

            // Variabel untuk menampung hasil
            finalScore: 0,
            timeTaken: { minutes: 0, seconds: 0 },

            init() {
                this.timeLeft = this.timeLimitInSeconds;
                this.questions.forEach(q => {
                    this.userAnswers[q.id] = (q.q_type_name === 'Checkbox' || q.q_type_name === 'Reorder') ? [] : null;
                });
            },

            // Computed Properties
            get progressPercentage() { return ((this.currentQuestionIndex + 1) / this.questions.length) * 100; },
            get formattedTime() {
                const minutes = Math.floor(this.timeLeft / 60);
                const seconds = this.timeLeft % 60;
                return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            },

            // Methods
            startTimer() {
                this.timerInterval = setInterval(() => {
                    if (this.timeLeft > 0) {
                        this.timeLeft--;
                    } else {
                        this.submitQuiz();
                    }
                }, 1000);
            },
            
            selectAnswer(questionId, answer) { this.userAnswers[questionId] = answer; },
            selectCheckboxAnswer(questionId, choiceId) {
                const answers = this.userAnswers[questionId];
                const index = answers.indexOf(choiceId);
                if (index === -1) { answers.push(choiceId); } else { answers.splice(index, 1); }
            },
            selectReorderAnswer(questionId, choiceId, order) {
                let answers = this.userAnswers[questionId].filter(a => a.choice_id !== choiceId);
                if (order) { answers.push({ choice_id: choiceId, order: parseInt(order) }); }
                this.userAnswers[questionId] = answers;
            },
            getReorderValue(questionId, choiceId) {
                const answer = this.userAnswers[questionId]?.find(a => a.choice_id === choiceId);
                return answer ? answer.order : '';
            },

            nextQuestion() { if (this.currentQuestionIndex < this.questions.length - 1) { this.currentQuestionIndex++; } },
            previousQuestion() { if (this.currentQuestionIndex > 0) { this.currentQuestionIndex--; } },
            
            submitQuiz() {
                clearInterval(this.timerInterval);

                const totalTime = this.timeLimitInSeconds;
                const timeSpentInSeconds = totalTime - this.timeLeft;
                this.timeTaken.minutes = Math.floor(timeSpentInSeconds / 60);
                this.timeTaken.seconds = timeSpentInSeconds % 60;

                const payload = {
                    topic_id: {{ $topic->topic_id }},
                    answers: this.userAnswers 
                };

                this.isFinished = true;
                this.finalScore = '...'; // Tampilkan loading

                fetch("{{ route('quiz.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => {
                    if (!response.ok) {
                        // Jika response dari server adalah error (misal: 500)
                        // Kita akan coba baca pesan error dari server
                        return response.text().then(text => { 
                            throw new Error(`Server Error: ${response.status} - ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Cek jika 'score' ada di dalam data
                    if (typeof data.score !== 'undefined') {
                        this.finalScore = data.score;
                    } else {
                        // Jika tidak ada, berarti ada masalah format response
                        console.error('Server response is missing "score" property:', data);
                        this.finalScore = '?';
                    }
                })
                .catch(error => {
                    // Tangkap semua jenis error (network, server, dll)
                    console.error('Fetch Error:', error);
                    this.finalScore = 'X'; // Tanda 'X' untuk error
                    alert('An error occurred while submitting. Please check the browser console (F12) for details.');
                });
            }
        }
    }
</script>
@endpush
