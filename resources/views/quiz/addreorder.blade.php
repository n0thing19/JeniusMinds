@extends('layouts.quizeditor')

@section('title', 'Quiz Editor - Reorder')

@push('styles')
<style>
    /* Style for answer box with border */
    .answer-box {
        border: 2px solid #FFE2D6;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background-color: #FFE2D6;
        transition: all 0.2s ease;
    }
    .answer-box:hover {
        border-color: #F7B5A3;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    /* Style for ranking selector */
    .rank-selector {
        display: flex;
        gap: 8px;
    }
    .rank-option {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid rgb(228, 228, 228);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: white;
    }
    .rank-option.selected {
        background-color: #F7B5A3;
        color: white;
        border-color: #F7B5A3;
        transform: scale(1.05);
    }
    .rank-option.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #F9F9F9;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-8 py-8">
    
    <!-- Kotak Pertanyaan -->
    <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
        <textarea 
            rows="3" 
            placeholder="Type Question Here" 
            class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
        >Type Question Here</textarea>
    </div>

    <!-- Daftar Jawaban untuk Diurutkan -->
    <div class="space-y-4" id="answer-container">
        <!-- Opsi 1 -->
        <div class="answer-box answer-item" data-id="1">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 w-full">
                    <span class="text-lg font-semibold">1.</span>
                    <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none border-b-2 border-transparent focus:border-gray-300 transition-colors">
                </div>
                <div class="rank-selector">
                    <div class="rank-option" data-rank="1" onclick="selectRank(this, 1)">1</div>
                    <div class="rank-option" data-rank="2" onclick="selectRank(this, 2)">2</div>
                    <div class="rank-option" data-rank="3" onclick="selectRank(this, 3)">3</div>
                    <div class="rank-option" data-rank="4" onclick="selectRank(this, 4)">4</div>
                </div>
            </div>
        </div>
        
        <!-- Opsi 2 -->
        <div class="answer-box answer-item" data-id="2">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 w-full">
                    <span class="text-lg font-semibold">2.</span>
                    <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none border-b-2 border-transparent focus:border-gray-300 transition-colors">
                </div>
                <div class="rank-selector">
                    <div class="rank-option" data-rank="1" onclick="selectRank(this, 1)">1</div>
                    <div class="rank-option" data-rank="2" onclick="selectRank(this, 2)">2</div>
                    <div class="rank-option" data-rank="3" onclick="selectRank(this, 3)">3</div>
                    <div class="rank-option" data-rank="4" onclick="selectRank(this, 4)">4</div>
                </div>
            </div>
        </div>
        
        <!-- Opsi 3 -->
        <div class="answer-box answer-item" data-id="3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 w-full">
                    <span class="text-lg font-semibold">3.</span>
                    <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none border-b-2 border-transparent focus:border-gray-300 transition-colors">
                </div>
                <div class="rank-selector">
                    <div class="rank-option" data-rank="1" onclick="selectRank(this, 1)">1</div>
                    <div class="rank-option" data-rank="2" onclick="selectRank(this, 2)">2</div>
                    <div class="rank-option" data-rank="3" onclick="selectRank(this, 3)">3</div>
                    <div class="rank-option" data-rank="4" onclick="selectRank(this, 4)">4</div>
                </div>
            </div>
        </div>
        
        <!-- Opsi 4 -->
        <div class="answer-box answer-item" data-id="4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 w-full">
                    <span class="text-lg font-semibold">4.</span>
                    <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none border-b-2 border-transparent focus:border-gray-300 transition-colors">
                </div>
                <div class="rank-selector">
                    <div class="rank-option" data-rank="1" onclick="selectRank(this, 1)">1</div>
                    <div class="rank-option" data-rank="2" onclick="selectRank(this, 2)">2</div>
                    <div class="rank-option" data-rank="3" onclick="selectRank(this, 3)">3</div>
                    <div class="rank-option" data-rank="4" onclick="selectRank(this, 4)">4</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Submit Button -->
    <div class="mt-8 flex justify-center">
        <button onclick="validateRanking()" class="bg-[#F7B5A3] text-black px-8 py-3 rounded-xl font-bold text-lg hover:brightness-105 transition hover:shadow-md">
            Submit Jawaban
        </button>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
    let selectedRanks = {};
    
    function selectRank(element, rank) {
        const answerItem = element.closest('.answer-item');
        const answerId = answerItem.getAttribute('data-id');
        
        // Cek apakah rank ini sudah digunakan oleh baris lain
        const usedRanks = Object.values(selectedRanks);
        if (usedRanks.includes(rank) && selectedRanks[answerId] !== rank) {
            console.warn(`Rank ${rank} is already taken.`);
            return; // Jangan lakukan apa-apa jika rank sudah diambil
        }
        
        // Hapus rank lama dari item ini jika ada
        delete selectedRanks[answerId];
        
        // Hapus kelas 'selected' dari semua opsi di baris ini
        const allOptionsInRow = answerItem.querySelectorAll('.rank-option');
        allOptionsInRow.forEach(opt => opt.classList.remove('selected'));
        
        // Tambahkan kelas 'selected' ke opsi yang diklik
        element.classList.add('selected');
        
        // Simpan rank yang baru dipilih
        selectedRanks[answerId] = rank;
        
        // Perbarui UI untuk menunjukkan rank mana yang sudah diambil
        updateRankAvailability();
    }
    
    function updateRankAvailability() {
        const allRankOptions = document.querySelectorAll('.rank-option');
        const usedRanks = Object.values(selectedRanks);
        
        allRankOptions.forEach(option => {
            const rank = parseInt(option.getAttribute('data-rank'));
            const answerItem = option.closest('.answer-item');
            const answerId = answerItem.getAttribute('data-id');
            const isCurrentSelection = selectedRanks[answerId] === rank;
            
            // Nonaktifkan jika rank sudah digunakan oleh baris LAIN
            if (usedRanks.includes(rank) && !isCurrentSelection) {
                option.classList.add('disabled');
            } else {
                option.classList.remove('disabled');
            }
        });
    }
    
    function validateRanking() {
        const allAnswers = document.querySelectorAll('.answer-item');
        const selectedCount = Object.keys(selectedRanks).length;
        
        if (selectedCount < allAnswers.length) {
            // Mengganti alert dengan console.log
            console.error('Silakan beri peringkat untuk semua opsi jawaban!');
            return;
        }
        
        const rankValues = Object.values(selectedRanks);
        const uniqueRanks = [...new Set(rankValues)];
        
        if (uniqueRanks.length !== rankValues.length) {
            // Mengganti alert dengan console.log
            console.error('Tidak boleh ada peringkat yang sama!');
            return;
        }
        
        // Jika validasi lolos
        const result = Object.entries(selectedRanks)
            .sort(([, rankA], [, rankB]) => rankA - rankB)
            .map(([id, rank]) => 
                `${rank}. ${document.querySelector(`.answer-item[data-id="${id}"] input`).value}`
            ).join('\n');
            
        console.log('Jawaban berhasil disimpan!\n\nHasil ranking:\n' + result);
    }
</script>
@endpush
