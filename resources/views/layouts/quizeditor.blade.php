<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quiz Editor') - Jenius Minds</title>

    {{-- Aset CSS dan Font --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Style dasar dan kustom untuk editor --}}
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFBF5;
        }
        .brand-border { border-color: #F3EAE6; }
        .brand-pink-light { background-color: #FFFBF5; }

        /* Style untuk tombol navigasi pertanyaan di footer */
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
        .question-nav-btn.active {
            background-color: #FFE3D6;
            color: black;
            border-color: #F7B5A3;
        }
        
        /* Utility untuk menyembunyikan scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- Stack untuk style tambahan dari halaman child --}}
    @stack('styles')
</head>
<body class="text-gray-700">

    <!-- ===== Header Khusus Quiz Editor (Dengan Logo) ===== -->
    <header class="sticky top-0 bg-[#FFFAF3]/70 backdrop-blur-lg z-20 shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between items-center">
                <!-- Logo di Kiri -->
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    <img src="{{ asset('assets/Logo.png') }}" alt="Logo Jenius Minds" class="w-14 h-14">
                    <span class="text-xl font-bold text-gray-800 group-hover:text-[#EEA99D] transition-colors">Jenius Minds</span>
                </a>

                <!-- Tombol di Kanan -->
                <div class="flex items-center space-x-4">
                    <a href="{{-- route('home') or desired cancel route --}}" class="text-gray-600 hover:text-gray-900 font-bold transition-colors">Cancel</a>
                    <a href="#" class="bg-[#F5B9B0] text-black px-6 py-2 rounded-lg font-bold shadow-sm hover:brightness-105 transition">Done</a>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== Konten Utama dari Halaman Editor (e.g., addbutton) ===== -->
    <main>
        @yield('content')
    </main>

    <!-- ===== Footer Khusus Quiz Editor ===== -->
    <footer class="fixed bottom-0 left-0 right-0 bg-[#FFFBF5] p-4 border-t brand-border">
        <div class="container mx-auto flex items-center justify-between space-x-4">
            
            <!-- Tombol Settings (Kiri) -->
            <button class="bg-[#F7B5A3] text-sm text-black px-4 h-14 rounded-xl font-bold flex items-center flex-shrink-0 hover:brightness-105 transition">Settings</button>
            
            <!-- Kontainer Nomor Pertanyaan yang Bisa di-scroll -->
            <div class="flex-grow overflow-x-auto no-scrollbar">
                <div class="flex justify-start items-center space-x-2 px-2">
                    {{-- Ini bisa di-generate secara dinamis dari controller --}}
                    <button class="question-nav-btn px-12 py-5 active">1</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">2</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">3</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">4</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">5</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">6</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">7</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">8</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">9</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">10</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">11</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">12</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">13</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">14</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">15</button>
                </div>
            </div>
            
            <!-- Tombol Tambah (Kanan) -->
            <button class="bg-gray-800 text-white w-14 h-14 rounded-xl font-bold text-2xl hover:bg-gray-700 transition-transform hover:scale-105 flex-shrink-0 flex items-center justify-center">+</button>

        </div>
    </footer>

    {{-- Stack untuk script tambahan dari halaman child --}}
    @stack('scripts')
</body>
</html>
