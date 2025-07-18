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
    <script>
        // Membuat variabel global untuk konfigurasi kuis
        window.quizConfig = {
            // Jika $topic ada, gunakan ID-nya, jika tidak, gunakan null
            topicId: {{ $topic->topic_id ?? 'null' }} 
        };
    </script>
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
            border-radius: 0.75rem; /* rounded-xl */
            border: 2px solid #F3EAE6;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
        }
        .question-nav-item {
            position: relative; /* Kunci untuk positioning absolut */
            flex-shrink: 0;
        }

        /* Gaya untuk ikon hapus yang menempel */
        .delete-icon-overlay {
            position: absolute;
            top: 1px;      /* Sesuaikan posisi vertikal */
            right: 1px;    /*Sesuaikan posisi horizontal */
            width: 28px;
            height: 28px;
            background-color: #ef4444; /* Merah */
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            border: 2px solid #FFFBF5; /* Cocokkan dengan warna background footer */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.2s ease-in-out;
        }

        .delete-icon-overlay:hover {
            background-color: #dc2626; /* Merah lebih gelap saat hover */
            transform: scale(1.1);
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
            <a href="{{ route('quiz.editor') }}" id="add-question-btn" class="bg-gray-800 text-white w-14 h-14 rounded-xl font-bold text-2xl hover:bg-gray-700 flex-shrink-0 flex items-center justify-center">+</a>
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
    <form id="main-quiz-form" 
        action="{{ $topic ? route('quiz.update', $topic) : route('quiz.store.all') }}" 
        method="POST" 
        class="hidden">
        @csrf
        @if($topic)
            @method('PATCH')
        @endif
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
            newTopicName: '{{ $topic->topic_name ?? '' }}',
            selectedSubjectId: '{{ $topic->subject_id ?? '' }}',
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
        // ====================== GANTI FUNGSI DI BAWAH INI ======================
        const renderFooterNav = () => {
            const pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
            if (!navContainer) return;
            navContainer.innerHTML = ''; // Kosongkan navigasi

            const urlParams = new URLSearchParams(window.location.search);
            // Dapatkan index yang sedang aktif dari URL
            const activeIndex = urlParams.has('edit') ? parseInt(urlParams.get('edit'), 10) : -1;

            if (pendingQuestions.length > 0) {
                pendingQuestions.forEach((question, index) => {
                    // 1. Buat div sebagai wadah utama untuk setiap item
                    const navItemContainer = document.createElement('div');
                    navItemContainer.className = 'question-nav-item';

                    // 2. Buat tombol link nomor seperti biasa
                    const buttonLink = document.createElement('a');
                    // ... (logika untuk menentukan pathSegment dan href tetap sama)
                    const qTypeName = (question.q_type_name || '').toLowerCase().replace(/\s+/g, '');
                    let pathSegment;
                    switch(qTypeName) {
                        case 'button': pathSegment = 'addbutton'; break;
                        case 'checkbox': pathSegment = 'addcheckbox'; break;
                        case 'typeanswer': pathSegment = 'typeanswer'; break;
                        case 'reorder': pathSegment = 'addreorder'; break;
                        default: pathSegment = 'addbutton';
                    }
                    let href = `{{ url('/quiz') }}/${pathSegment}?edit=${index}`;
                    if (window.quizConfig.topicId) {
                        href += `&topic_id=${window.quizConfig.topicId}`;
                    }
                    buttonLink.href = href;
                    buttonLink.textContent = index + 1;
                    buttonLink.className = 'question-nav-btn'; // Class styling tombol nomor

                    // Beri style khusus jika nomor ini sedang aktif
                    if (index === activeIndex) {
                        buttonLink.classList.add('px-12', 'py-5', 'bg-[#FFE3D6]', 'text-black', 'border-[#F7B5A3]');
                    } else {
                        buttonLink.classList.add('px-12', 'py-5', 'bg-brand-pink-light');
                    }

                    // Masukkan tombol nomor ke dalam wadahnya
                    navItemContainer.appendChild(buttonLink);

                    // 3. JIKA nomor ini sedang aktif, tambahkan ikon hapus!
                    if (index === activeIndex) {
                        const deleteIcon = document.createElement('i');
                        deleteIcon.className = 'fa-solid fa-trash delete-icon-overlay';
                        
                        // Tambahkan fungsi hapus saat ikon diklik
deleteIcon.addEventListener('click', function(e) {
    e.stopPropagation(); // Mencegah link navigasi ter-klik

    if (!confirm('Are you sure you want to remove this question?')) {
        return;
    }

    // 1. Ambil & perbarui data dari sessionStorage
    let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
    pendingQuestions.splice(index, 1); // Hapus pertanyaan dari array
    sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));

    // 2. Render ulang navigasi di footer SECARA LANGSUNG
    // Ini akan menghilangkan nomor soal yang dihapus secara instan.
    renderFooterNav();

    // 3. Ubah konten utama halaman untuk memberi feedback
    // Ini menggantikan konten form dengan pesan konfirmasi.
    const mainContent = document.querySelector('main');
    if (mainContent) {
        mainContent.innerHTML = `
            <div class="container mx-auto px-6 py-12 text-center">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg inline-block">
                    <h1 class="text-2xl font-bold">Question Removed</h1>
                    <p class="mt-2">This question has been removed from the session.</p>
                    <p>Your changes will be permanent after you click "Done".</p>
                </div>
            </div>
        `;
    }

    // 4. (Opsional) Hapus ikon hapus itu sendiri setelah diklik
    this.remove();
});


                        
                        // Masukkan ikon hapus ke dalam wadah yang sama
                        navItemContainer.appendChild(deleteIcon);
                    }
                    
                    // 4. Masukkan seluruh item (wadah) ke kontainer navigasi
                    navContainer.appendChild(navItemContainer);
                });
            } else {
                navContainer.innerHTML = '<p class="text-gray-500 px-4">Click \'+\' to add a question.</p>';
            }
        };
        // ====================== AKHIR FUNGSI PENGGANTI =======================



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
            const addQuestionBtn = document.getElementById('add-question-btn');
            if (addQuestionBtn && window.quizConfig.topicId) {
                // Jika sedang mode edit (topicId ada), ubah link tombol '+'
                // agar menyertakan topic_id.
                addQuestionBtn.href = `{{ route('quiz.editor') }}?topic_id=${window.quizConfig.topicId}`;
            }
                // --- TAMBAHKAN BLOK KODE BARU DI BAWAH INI ---

            const deleteBtn = document.getElementById('delete-question-btn');
            const urlParams = new URLSearchParams(window.location.search);
            const editIndex = urlParams.get('edit'); // Cek apakah ada parameter 'edit' di URL

            // Tampilkan tombol hanya jika kita sedang di halaman edit pertanyaan
            if (deleteBtn && editIndex !== null) {
                deleteBtn.style.display = 'flex'; // Tampilkan tombol hapus

                // Tambahkan event listener untuk klik
                deleteBtn.addEventListener('click', function() {
                    if (!confirm('Are you sure you want to delete this question?')) {
                        return; // Batalkan jika pengguna klik 'Cancel'
                    }

                    const currentIndex = parseInt(editIndex, 10);
                    let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];

                    // Hapus pertanyaan dari array berdasarkan index-nya
                    if (currentIndex >= 0 && currentIndex < pendingQuestions.length) {
                        pendingQuestions.splice(currentIndex, 1);
                    }
                    
                    // Simpan kembali array yang sudah dimodifikasi ke sessionStorage
                    sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));

                    // Arahkan kembali pengguna ke halaman editor utama
                    alert('Question deleted.');
                    let redirectUrl = `{{ route('quiz.editor') }}`;
                    if (window.quizConfig.topicId) {
                        redirectUrl += `?topic_id=${window.quizConfig.topicId}`;
                    }
                    window.location.href = redirectUrl;
                });
            }
            // --- AKHIR DARI BLOK KODE TAMBAHAN ---

        });
    </script>
</body>
</html>
