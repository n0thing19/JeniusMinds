@extends('layouts.quizeditor')

@section('title', 'Quiz Editor - Reorder')

@push('styles')
<style>
    /* --- Answer Box Styles --- */
    .answer-box {
        background-color: #FFE2D6;
        border: 2px solid #FFE2D6;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }
    
    /* --- Rank Selector Styles --- */
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
            id="question_text"
            rows="3" 
            placeholder="Type Question Here" 
            class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
        ></textarea>
    </div>

    <!-- Daftar Jawaban untuk Diurutkan -->
    <div class="space-y-4" id="answer-container">
        @for ($i = 0; $i < 4; $i++)
        <div class="answer-box answer-item">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 w-full">
                    <span class="text-lg font-semibold">{{ $i + 1 }}.</span>
                    <input type="text" placeholder="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none border-b-2 border-transparent focus:border-gray-300 transition-colors">
                </div>
                <div class="rank-selector">
                    @for ($rank = 1; $rank <= 4; $rank++)
                    <div class="rank-option" data-rank="{{ $rank }}">{{ $rank }}</div>
                    @endfor
                </div>
            </div>
        </div>
        @endfor
    </div>
    
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const storageKey = 'pendingQuestions';
    const questionTextEl = document.getElementById('question_text');
    const answerItems = Array.from(document.querySelectorAll('.answer-item'));
    const choiceInputs = answerItems.map(item => item.querySelector('input[type="text"]'));

    const urlParams = new URLSearchParams(window.location.search);
    const questionIndex = parseInt(urlParams.get('edit'), 10);

    let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
    let currentQuestion = pendingQuestions[questionIndex] || {};
    
    // Format: [rank_for_choice_0, rank_for_choice_1, ...]
    // Contoh: [2, 4, 1, 3] berarti jawaban pertama urutan ke-2, dst.
    // Nilai -1 berarti belum diberi peringkat.
    let correctOrder = currentQuestion.correct_order || [-1, -1, -1, -1];

    const loadQuestionData = () => {
        questionTextEl.value = currentQuestion.question_text || '';
        if (currentQuestion.choices) {
            choiceInputs.forEach((input, i) => {
                input.value = currentQuestion.choices[i] || '';
            });
        }
        updateRankUI();
    };

    const saveQuestionData = () => {
        // Hapus properti yang tidak relevan dari tipe soal lain
        delete currentQuestion.correct_choice;
        delete currentQuestion.correct_choices;

        const data = {
            ...currentQuestion,
            q_type_name: 'Reorder',
            question_text: questionTextEl.value.trim(),
            choices: choiceInputs.map(input => input.value.trim()),
            correct_order: correctOrder
        };
        pendingQuestions[questionIndex] = data;
        sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));
    };

    const updateRankUI = () => {
        const allRankOptions = document.querySelectorAll('.rank-option');
        const usedRanks = correctOrder.filter(rank => rank !== -1);
        
        allRankOptions.forEach(option => {
            const rank = parseInt(option.dataset.rank);
            option.classList.remove('selected', 'disabled');

            // Nonaktifkan jika rank ini sudah digunakan oleh baris LAIN
            if (usedRanks.includes(rank)) {
                option.classList.add('disabled');
            }
        });

        // Terapkan style 'selected' untuk rank yang sudah dipilih di setiap baris
        answerItems.forEach((item, itemIndex) => {
            const selectedRank = correctOrder[itemIndex];
            if (selectedRank !== -1) {
                const selectedOption = item.querySelector(`.rank-option[data-rank="${selectedRank}"]`);
                if (selectedOption) {
                    selectedOption.classList.remove('disabled'); // Aktifkan kembali agar bisa diklik lagi
                    selectedOption.classList.add('selected');
                }
            }
        });
    };

    answerItems.forEach((item, itemIndex) => {
        const rankOptions = item.querySelectorAll('.rank-option');
        rankOptions.forEach(option => {
            option.addEventListener('click', () => {
                const rank = parseInt(option.dataset.rank);
                const isAlreadyUsed = correctOrder.some((r, i) => r === rank && i !== itemIndex);

                if (isAlreadyUsed) return; // Jangan lakukan apa-apa jika rank sudah diambil

                // Jika mengklik rank yang sama lagi, batalkan pilihan (set ke -1)
                if (correctOrder[itemIndex] === rank) {
                    correctOrder[itemIndex] = -1;
                } else {
                    correctOrder[itemIndex] = rank;
                }
                
                updateRankUI();
                saveQuestionData();
            });
        });
    });

    questionTextEl.addEventListener('input', saveQuestionData);
    choiceInputs.forEach(input => input.addEventListener('input', saveQuestionData));

    loadQuestionData();
});
</script>
@endpush
