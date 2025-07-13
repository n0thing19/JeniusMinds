@extends('layouts.quizeditor')

@section('title', 'Quiz Editor')

@push('styles')
{{-- Style ini 100% sama dengan file asli Anda --}}
<style>
    .question-type-card { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border: 2px solid transparent; background-color: #F7B5A3; }
    .question-type-card:hover { transform: translateY(-5px) scale(1.02); box-shadow: 0 15px 30px -10px rgba(238, 169, 157, 0.3); }
    .icon-box { width: 4rem; height: 4rem; display: flex; align-items: center; justify-content: center; color: #000000; }
    .icon-box .fa-square, .icon-box .fa-check-square { font-size: 2rem; }
    .icon-box .fa-layer-group { font-size: 1.8rem; }
    .icon-box .custom-icon-text { font-size: 1.8rem; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    {{-- BLOK BARU: Dropdown Topik dan Pesan Error/Sukses --}}
    <div class="mb-8">
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                <p class="font-bold">Could not save the quiz:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif
    
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
            <label for="topic_id" class="block text-xl font-bold text-gray-800 mb-2">Select Topic for this Quiz</label>
            <p class="text-gray-500 mb-4">This topic will be applied to all questions in this session.</p>
            @php
                $topics = \App\Models\Quiz\Topic::with('subject')->orderBy('subject_id')->get();
            @endphp
            {{-- `name="topic_id"` ini akan ditangkap oleh form utama di layout --}}
            <select name="topic_id" id="topic_id" class="w-full p-4 border-2 border-[#F3EAE6] rounded-xl text-lg focus:outline-none focus:ring-2 focus:ring-[#EEA99D]">
                <option value="">-- Choose a topic --</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->topic_id }}">{{ $topic->subject->subject_name }} - {{ $topic->topic_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- KONTEN ASLI ANDA: Pemilihan Tipe Soal --}}
    <h1 class="text-center text-3xl font-extrabold text-gray-800 mb-8">CHOOSE QUESTION TYPE</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <a href="#" id="add-button-type" class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
            <div class="icon-box"><i class="far fa-square"></i></div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">BUTTONS</h2>
                <p class="text-gray-500">One correct answer</p>
            </div>
        </a>
        <a href="{{ route('quiz.addcheckbox') }}" class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
            <div class="icon-box"><i class="far fa-check-square"></i></div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">CHECKBOXES</h2>
                <p class="text-gray-500">Multiple correct answers</p>
            </div>
        </a>
        <a href="{{ route('quiz.addreorder') }}" class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
            <div class="icon-box"><i class="fas fa-layer-group"></i></div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">REORDER</h2>
                <p class="text-gray-500">Place answers in the correct order</p>
            </div>
        </a>
        <a href="{{ route('quiz.typeanswer') }}" class="question-type-card p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
            <div class="icon-box"><span class="custom-icon-text">Aa</span></div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">TYPE ANSWER</h2>
                <p class="text-gray-500">Type the correct answer</p>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const storageKey = 'pendingQuestions';

    function prepareNewQuestion(questionTypeName, routeName) {
        // 1. Dapatkan data yang sudah ada
        let pendingQuestions = JSON.parse(sessionStorage.getItem(storageKey)) || [];
        const newIndex = pendingQuestions.length;

        // 2. Siapkan objek soal baru yang kosong
        const newQuestionData = {
            q_type_name: questionTypeName,
            question_text: '',
            choices: ['', '', '', ''],
            correct_choice: -1
        };
        
        // 3. Tambahkan ke array dan simpan kembali ke sessionStorage
        pendingQuestions.push(newQuestionData);
        sessionStorage.setItem(storageKey, JSON.stringify(pendingQuestions));
        
        // 4. (PERBAIKAN) Tunggu sebentar sebelum redirect
        // Ini memberi waktu bagi browser untuk menyelesaikan penyimpanan
        // sebelum memuat halaman berikutnya.
        setTimeout(() => {
            window.location.href = `{{ url('/quiz') }}/${routeName}?edit=${newIndex}`;
        }, 100); // Penundaan 100 milidetik biasanya sudah cukup
    }

    // Event listener untuk tipe soal "BUTTONS"
    const addButtonBtn = document.getElementById('add-button-type');
    if (addButtonBtn) {
        addButtonBtn.addEventListener('click', function(e) {
            e.preventDefault();
            prepareNewQuestion('Button', 'addbutton');
        });
    }

    // Event listener untuk tipe soal "CHECKBOXES" (jika ada)
    const addCheckboxBtn = document.getElementById('add-checkbox-type');
    if (addCheckboxBtn) {
        addCheckboxBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Ganti 'addcheckbox' dengan nama route Anda yang benar
            prepareNewQuestion('Checkbox', 'addcheckbox');
        });
    }

    // Tambahkan event listener lain di sini jika diperlukan
});
</script>
@endpush
