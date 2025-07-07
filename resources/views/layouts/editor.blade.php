@extends('layouts.app')

{{-- Menambahkan style khusus untuk halaman editor --}}
@push('styles')
    <style>
        body {
            padding-bottom: 120px; /* Memberi ruang untuk footer navigasi */
        }
        .question-nav-btn {
            transition: all 0.2s ease;
            flex-shrink: 0;
            font-weight: 700;
            border-radius: 0.75rem;
            border: 2px solid #F3EAE6;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .question-nav-btn.active {
            background-color: #FFE3D6;
            color: black;
            border-color: #F7B5A3;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
@endpush

{{-- Mendefinisikan konten utama yang akan diisi oleh child view (addbutton, dll) --}}
@section('content')
<div class="container mx-auto px-8 py-16">
    {{-- Di sini konten spesifik dari setiap halaman editor akan ditampilkan --}}
    @yield('editor_content')
</div>

<!-- ===== Navigasi Bawah Khusus Editor ===== -->
<footer class="fixed bottom-0 left-0 right-0 bg-[#FFFBF5]/80 backdrop-blur-sm p-4 border-t brand-border">
    <div class="container mx-auto flex items-center justify-between space-x-4">
        
        <!-- Tombol Settings (Kiri) -->
        <button class="bg-[#F7B5A3] text-sm text-black px-4 h-14 rounded-xl font-bold flex items-center flex-shrink-0 hover:brightness-105 transition">Settings</button>
        
        <!-- Kontainer Nomor Pertanyaan yang Bisa di-scroll -->
        <div class="flex-grow overflow-x-auto no-scrollbar">
            <div class="flex justify-start items-center space-x-2 px-2">
                {{-- Contoh tombol navigasi, ini bisa dibuat dinamis dengan data dari controller --}}
                <button class="question-nav-btn active">1</button>
                <button class="question-nav-btn bg-white">2</button>
                <button class="question-nav-btn bg-white">3</button>
                {{-- ... tambahkan lebih banyak jika perlu --}}
            </div>
        </div>
        
        <!-- Tombol Tambah (Kanan) -->
        <button class="bg-gray-800 text-white w-14 h-14 rounded-xl font-bold text-2xl hover:bg-gray-700 transition-transform hover:scale-105 flex-shrink-0 flex items-center justify-center">+</button>

    </div>
</footer>
@endsection
