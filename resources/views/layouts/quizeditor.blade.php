<!DOCTYPE html>
<html lang="id">
<head>
    {{-- Bagian head tidak berubah --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quiz Editor') - Jenius Minds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    </style>
    @stack('styles')
</head>
<body class="text-gray-700">

<form action="{{ route('quiz.store.all') }}" method="POST" id="main-quiz-form">
    @csrf
    <input type="hidden" name="questions_data" id="questions_data">

    <header class="sticky top-0 bg-[#FFFAF3]/70 backdrop-blur-lg z-20 shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    <img src="{{ asset('assets/Logo.png') }}" alt="Logo Jenius Minds" class="w-14 h-14">
                    <span class="text-xl font-bold text-gray-800 group-hover:text-[#EEA99D] transition-colors">Jenius Minds</span>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('quiz.editor') }}" id="cancel-btn" class="text-gray-600 hover:text-gray-900 font-bold transition-colors">Cancel</a>
                    <button type="submit" class="bg-[#F5B9B0] text-black px-6 py-2 rounded-lg font-bold shadow-sm hover:brightness-105 transition">Done</button>
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
</form>

{{-- SCRIPT DI LAYOUT TIDAK PERLU DIUBAH LAGI, SUDAH BENAR --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const storageKey = 'pendingQuestions';
    const navContainer = document.getElementById('question-nav-container');

    // --- LOGIKA UTAMA YANG DIPERBAIKI ---
    // Fungsi ini akan menjadi satu-satunya sumber kebenaran untuk rendering footer.
    const renderFooterNav = () => {
        const pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
        if (!navContainer) return;
        navContainer.innerHTML = '';
        
        // 1. Dapatkan index aktif LANGSUNG dari URL. Ini kunci perbaikannya.
        const urlParams = new URLSearchParams(window.location.search);
        let activeIndex = -1;
        if (urlParams.has('edit')) {
            activeIndex = parseInt(urlParams.get('edit'), 10);
            // 2. Perbarui juga sessionStorage agar state tetap konsisten
            sessionStorage.setItem('currentQuestionIndex', activeIndex);
        }

        if (pendingQuestions.length > 0) {
            pendingQuestions.forEach((question, index) => {
                const button = document.createElement('a');
                const page = (question.q_type_name || 'button').toLowerCase();
                const routeName = `add${page}`;
                button.href = `{{ url('/quiz') }}/${routeName}?edit=${index}`;
                
                button.textContent = index + 1;
                button.className = 'question-nav-btn';

                // 3. Gunakan activeIndex yang baru kita dapatkan dari URL
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

    // --- Sisa script tidak banyak berubah ---
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

    const mainForm = document.getElementById('main-quiz-form');
    if (mainForm) {
        mainForm.addEventListener('submit', function(e) {
            // ... (logika submit tidak berubah)
            const finalQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
            if (finalQuestions.length === 0) {
                alert('Please add at least one question before saving.');
                e.preventDefault();
                return;
            }
            document.getElementById('questions_data').value = JSON.stringify(finalQuestions);
            sessionStorage.removeItem(storageKey);
            sessionStorage.removeItem('currentQuestionIndex');
        });
    }

    @if(session('success') || $errors->any())
        sessionStorage.removeItem(storageKey);
        sessionStorage.removeItem('currentQuestionIndex');
    @endif

    // Panggil fungsi render utama
    renderFooterNav();
});
</script>
@stack('scripts')

</body>
</html>