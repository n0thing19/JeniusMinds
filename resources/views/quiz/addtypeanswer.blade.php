@extends('layouts.quizeditor')

@section('title', 'Quiz Editor - Type Answer')

@push('styles')
<style>
    /* Anda bisa menambahkan style khusus untuk halaman ini jika diperlukan */
</style>
@endpush

@section('content')
<div class="container mx-auto px-8 py-8">
    
    <!-- Kotak Pertanyaan -->
    <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
        <textarea 
            id="question_text" {{-- ID untuk JavaScript --}}
            rows="3" 
            placeholder="Type Question Here" 
            class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
        ></textarea>
    </div>

    <!-- Kotak Jawaban -->
    <div class="bg-white p-12 rounded-xl shadow-lg brand-border border-2">
        <textarea 
            id="answer_text" {{-- ID untuk JavaScript --}}
            rows="5" 
            placeholder="Type Answer Here" 
            class="w-full bg-transparent text-xl font-semibold placeholder:text-gray-500/80 focus:outline-none resize-none"
        ></textarea>
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
    const answerTextEl = document.getElementById('answer_text');

    // Mendapatkan index pertanyaan dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const questionIndex = parseInt(urlParams.get('edit'), 10);

    // Mengambil data yang sudah ada dari sessionStorage
    let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
    let currentQuestion = pendingQuestions[questionIndex] || {};

    // Fungsi untuk memuat data ke dalam form
    const loadQuestionData = () => {
        questionTextEl.value = currentQuestion.question_text || '';
        // Untuk tipe "Type Answer", jawaban disimpan sebagai elemen pertama dalam array 'choices'
        answerTextEl.value = currentQuestion.choices ? (currentQuestion.choices[0] || '') : '';
    };

    // Fungsi untuk menyimpan perubahan ke sessionStorage
    const saveQuestionData = () => {
        // Hapus properti yang tidak relevan dari tipe soal lain
        delete currentQuestion.correct_choice;
        delete currentQuestion.correct_choices;

        const data = {
            ...currentQuestion,
            q_type_name: 'TypeAnswer',
            question_text: questionTextEl.value.trim(),
            // Simpan jawaban sebagai elemen pertama dalam array 'choices'.
            // Ini agar struktur data konsisten dengan tipe soal lain yang juga menggunakan 'choices'.
            choices: [answerTextEl.value.trim(), '', '', ''] 
        };
        
        pendingQuestions[questionIndex] = data;
        sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));
    };

    // Tambahkan event listener untuk menyimpan setiap kali ada ketikan
    questionTextEl.addEventListener('input', saveQuestionData);
    answerTextEl.addEventListener('input', saveQuestionData);

    // Muat data soal saat halaman pertama kali dibuka
    loadQuestionData();
});
</script>
@endpush
