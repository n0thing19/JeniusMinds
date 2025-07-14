<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quiz Editor') - Jenius Minds</title>
    
    {{-- Aset CSS dan Font --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- PENTING: Menambahkan 'defer' agar Alpine.js dieksekusi setelah halaman dimuat --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #FFFBF5; 
        }
        .brand-border { 
            border-color: #F3EAE6; 
        }
        .question-nav-btn {
            transition: all 0.2s ease;
            flex-shrink: 0;
            font-weight: 700;
            border-radius: 0.75rem;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="text-gray-700" x-data="quizEditorLayout()">

    <header class="sticky top-0 bg-[#FFFAF3]/70 backdrop-blur-lg z-20 shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    <img src="{{ asset('assets/Logo.png') }}" alt="Logo Jenius Minds" class="w-14 h-14">
                    <span class="text-xl font-bold text-gray-800">Jenius Minds</span>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('quiz.editor') }}" id="cancel-btn" class="text-gray-600 hover:text-gray-900 font-bold transition-colors">Cancel</a>
                    <button @click="openSaveModal()" type="button" class="bg-[#F5B9B0] text-black px-6 py-2 rounded-lg font-bold shadow-sm hover:brightness-105 transition">Done</button>
                </div>
            </div>
        </div>
    </header>

    <main class="pb-32">
        @yield('content')
    </main>

    <footer class="fixed bottom-0 left-0 right-0 bg-[#FFFBF5] p-4 border-t brand-border z-10">
        <div class="container mx-auto flex items-center justify-between space-x-4">
            <button type="button" class="bg-[#F7B5A3] text-sm text-black px-4 h-14 rounded-xl font-bold flex items-center flex-shrink-0 hover:brightness-105 transition">Settings</button>
            <div class="flex-grow overflow-x-auto no-scrollbar">
                <div id="question-nav-container" class="flex justify-start items-center space-x-2 px-2"></div>
            </div>
            <a href="{{ route('quiz.editor') }}" class="bg-gray-800 text-white w-14 h-14 rounded-xl font-bold text-2xl hover:bg-gray-700 flex-shrink-0 flex items-center justify-center">+</a>
        </div>
    </footer>

    <!-- ===== Modal untuk Menyimpan Kuis ===== -->
    <div x-show="isSaveModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4" x-cloak>
        <div @click.away="isSaveModalOpen = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8" x-transition>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Finalize Your Quiz</h2>
            <form id="save-quiz-details-form" @submit.prevent="submitFinalQuiz()">
                <div class="space-y-6">
                    <div>
                        <label for="new_topic_name" class="block font-semibold text-gray-700 mb-1">Quiz Topic Name</label>
                        <input type="text" id="new_topic_name" x-model="newTopicName" class="w-full border-2 brand-border rounded-lg p-3 focus:ring-2 focus:ring-[#EEA99D]/50 focus:border-[#EEA99D] outline-none" placeholder="e.g., Algebra Basics" required>
                    </div>
                    <div>
                        <label for="subject_id" class="block font-semibold text-gray-700 mb-1">Subject</label>
                        <select id="subject_id" x-model="selectedSubjectId" class="w-full border-2 brand-border rounded-lg p-3 focus:ring-2 focus:ring-[#EEA99D]/50 focus:border-[#EEA99D] outline-none bg-white" required>
                            <option value="" disabled>-- Select a Subject --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end items-center space-x-4 mt-8">
                    <button type="button" @click="isSaveModalOpen = false" class="font-bold text-gray-600 hover:text-gray-900 transition-colors">Cancel</button>
                    <button type="submit" class="bg-[#F5B9B0] text-black px-8 py-3 rounded-lg font-bold shadow-md hover:brightness-105 transition">Save Quiz</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form utama yang tersembunyi untuk dikirim ke backend -->
    <form id="main-quiz-form" action="{{ route('quiz.store.all') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="new_topic_name" x-model="newTopicName">
        <input type="hidden" name="subject_id" x-model="selectedSubjectId">
        <input type="hidden" name="questions_data" id="questions_data">
    </form>

    @stack('scripts')
    <script>
        // --- PERBAIKAN UTAMA: Menggunakan event listener 'alpine:init' ---
        document.addEventListener('alpine:init', () => {
            Alpine.data('quizEditorLayout', () => ({
                isSaveModalOpen: false,
                newTopicName: '',
                selectedSubjectId: '',

                openSaveModal() {
                    const pendingQuestions = JSON.parse(sessionStorage.getItem('pendingQuestions')) || [];
                    if (pendingQuestions.length === 0) {
                        alert('Please add at least one question before saving.');
                        return;
                    }
                    this.isSaveModalOpen = true;
                },

                submitFinalQuiz() {
                    if (!this.newTopicName.trim() || !this.selectedSubjectId) {
                        alert('Please fill in the topic name and select a subject.');
                        return;
                    }
                    
                    const finalQuestions = sessionStorage.getItem('pendingQuestions');
                    document.getElementById('questions_data').value = finalQuestions;
                    
                    document.getElementById('main-quiz-form').submit();
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const storageKey = 'pendingQuestions';
            const navContainer = document.getElementById('question-nav-container');

            const renderFooterNav = () => {
                const pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
                if (!navContainer) return;
                navContainer.innerHTML = '';
                
                const urlParams = new URLSearchParams(window.location.search);
                let activeIndex = -1;
                if (urlParams.has('edit')) {
                    activeIndex = parseInt(urlParams.get('edit'), 10);
                    sessionStorage.setItem('currentQuestionIndex', activeIndex);
                }

                if (pendingQuestions.length > 0) {
                    pendingQuestions.forEach((question, index) => {
                        const button = document.createElement('a');
                        // Perbaikan kecil untuk menangani nama tipe yang berbeda (misal: TypeAnswer)
                        const page = (question.q_type_name || 'button').toLowerCase().replace(/\s+/g, '');
                        const routeName = `add${page}`;
                        button.href = `{{ url('/quiz') }}/${routeName}?edit=${index}`;
                        
                        button.textContent = index + 1;
                        button.className = 'question-nav-btn';

                        if (index === activeIndex) {
                            button.classList.add('bg-gray-800', 'text-white');
                        } else {
                            button.classList.add('bg-white', 'text-gray-800', 'hover:bg-gray-100');
                        }
                        navContainer.appendChild(button);
                    });
                } else {
                    navContainer.innerHTML = '<p class="text-gray-500 px-4">Click \'+\' to add a question.</p>';
                }
            };

            const cancelBtn = document.getElementById('cancel-btn');
            if(cancelBtn) {
                cancelBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if(confirm('Are you sure? All unsaved questions will be lost.')) {
                        sessionStorage.removeItem(storageKey);
                        sessionStorage.removeItem('currentQuestionIndex');
                        window.location.href = this.href;
                    }
                });
            }

            @if(session('success') || $errors->any())
                sessionStorage.removeItem(storageKey);
                sessionStorage.removeItem('currentQuestionIndex');
            @endif

            renderFooterNav();
        });
    </script>
</body>
</html>
