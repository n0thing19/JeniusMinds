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
{{-- Menghapus tag <form> karena penyimpanan ditangani oleh JavaScript --}}
<div class="container mx-auto px-8 py-16">
    <!-- Kotak Pertanyaan -->
    <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
        {{-- Menambahkan ID agar bisa ditarget oleh JavaScript --}}
        <textarea 
            id="question_text"
            rows="3" 
            placeholder="Type Question Here" 
            class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
        ></textarea>
    </div>

    <!-- Grid Opsi Jawaban (Selectable) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Loop untuk 4 pilihan jawaban --}}
        @for ($i = 0; $i < 4; $i++)
        {{-- Menghapus onclick dan membiarkan JS yang menangani event --}}
        <div class="answer-card p-8 rounded-2xl flex items-center justify-between shadow-md">
            <input type="text" placeholder="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
            <div class="custom-checkbox ml-4">
                <i class="fas fa-check text-white text-lg"></i>
            </div>
        </div>
        @endfor
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kunci untuk menyimpan data di sessionStorage
    const storageKey = 'pendingQuestions';
    
    // Mengambil elemen-elemen dari DOM
    const questionTextEl = document.getElementById('question_text');
    const answerCards = Array.from(document.querySelectorAll('.answer-card'));
    const choiceInputs = answerCards.map(card => card.querySelector('input[type="text"]'));

    // Mendapatkan index pertanyaan dari URL (misal: ?edit=1)
    const urlParams = new URLSearchParams(window.location.search);
    const questionIndex = parseInt(urlParams.get('edit'), 10);

    // Mengambil data yang sudah ada dari sessionStorage atau membuat array kosong
    let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
    let currentQuestion = pendingQuestions[questionIndex] || {};
    
    // Menggunakan array untuk menyimpan index jawaban yang benar (karena bisa lebih dari satu)
    let correctChoiceIndices = currentQuestion.correct_choices || [];

    // Fungsi untuk memuat data dari 'currentQuestion' ke dalam form
    const loadQuestionData = () => {
        questionTextEl.value = currentQuestion.question_text || '';
        if (currentQuestion.choices) {
            choiceInputs.forEach((input, i) => {
                input.value = currentQuestion.choices[i] || '';
            });
        }
        updateCardStyles();
    };

    // Fungsi untuk menyimpan data form ke sessionStorage
    const saveQuestionData = () => {
        // PERBAIKAN: Hapus properti 'correct_choice' yang mungkin terbawa dari tipe soal lain
        delete currentQuestion.correct_choice;

        const data = {
            ...currentQuestion, // Pertahankan properti lain yang mungkin sudah ada
            q_type_name: 'Checkbox', // Pastikan tipe soal selalu tersimpan
            question_text: questionTextEl.value.trim(),
            choices: choiceInputs.map(input => input.value.trim()),
            correct_choices: correctChoiceIndices // Simpan array index jawaban benar
        };
        pendingQuestions[questionIndex] = data;
        sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));
    };

    // Fungsi untuk memperbarui tampilan visual kartu jawaban
    const updateCardStyles = () => {
        answerCards.forEach((card, index) => {
            // Periksa apakah index kartu ada di dalam array jawaban benar
            if (correctChoiceIndices.includes(index)) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    };

    // Menambahkan event listener untuk setiap kartu jawaban
    answerCards.forEach((card, index) => {
        card.addEventListener('click', () => {
            const pos = correctChoiceIndices.indexOf(index);
            if (pos === -1) {
                // Jika belum ada, tambahkan ke array
                correctChoiceIndices.push(index);
            } else {
                // Jika sudah ada, hapus dari array (toggle)
                correctChoiceIndices.splice(pos, 1);
            }
            // Perbarui tampilan dan simpan data
            updateCardStyles();
            saveQuestionData();
        });
    });

    // Menambahkan event listener untuk menyimpan setiap kali ada ketikan
    questionTextEl.addEventListener('input', saveQuestionData);
    choiceInputs.forEach(input => input.addEventListener('input', saveQuestionData));

    // Muat data soal saat halaman pertama kali dibuka
    loadQuestionData();
});
</script>
@endpush
