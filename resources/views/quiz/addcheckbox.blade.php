@extends('layouts.quizeditor')

@section('title', 'Quiz Editor - Checkbox')

@push('styles')
<style>
    /* --- Answer Card Styles --- */
    .answer-card {
        background-color: #FFE2D6;
        border: 3px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    /* --- Custom Checkbox Styles --- */
    .custom-checkbox {
        width: 28px;
        height: 28px;
        border: 3px solid rgb(248, 132, 100);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .custom-checkbox i {
        display: none; /* Sembunyikan ikon secara default */
    }
    
    /* --- Selected State Styles --- */
    .answer-card.selected {
        border-color:rgb(224, 126, 65);
        background-color:rgb(240, 207, 187);
    }

    .answer-card.selected .custom-checkbox {
        background-color: rgb(228, 167, 129);
        border-color: rgb(224, 126, 65);
    }

    .answer-card.selected .custom-checkbox i {
        display: block; /* Tampilkan ikon saat dipilih */
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-8 py-16">
    <!-- Kotak Pertanyaan -->
    <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
        <textarea 
            rows="3" 
            placeholder="Type Question Here" 
            class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
        >Type Question Here</textarea>
    </div>

    <!-- Grid Opsi Jawaban (Selectable) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Opsi 1 -->
        <div class="answer-card p-8 rounded-2xl flex items-center justify-between shadow-md" onclick="toggleSelect(this)">
            <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
            <div class="custom-checkbox ml-4">
                <i class="fas fa-check text-white text-lg"></i>
            </div>
        </div>
        <!-- Opsi 2 (Selected Example) -->
        <div class="answer-card selected p-8 rounded-2xl flex items-center justify-between shadow-md" onclick="toggleSelect(this)">
            <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
            <div class="custom-checkbox ml-4">
                <i class="fas fa-check text-white text-lg"></i>
            </div>
        </div>
        <!-- Opsi 3 -->
        <div class="answer-card p-8 rounded-2xl flex items-center justify-between shadow-md" onclick="toggleSelect(this)">
            <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
            <div class="custom-checkbox ml-4">
                <i class="fas fa-check text-white text-lg"></i>
            </div>
        </div>
        <!-- Opsi 4 -->
        <div class="answer-card p-8 rounded-2xl flex items-center justify-between shadow-md" onclick="toggleSelect(this)">
            <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
            <div class="custom-checkbox ml-4">
                <i class="fas fa-check text-white text-lg"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk mengubah kelas 'selected' pada kartu jawaban
    function toggleSelect(cardElement) {
        // Ini memungkinkan pemilihan beberapa jawaban.
        cardElement.classList.toggle('selected');
    }
</script>
@endpush
    