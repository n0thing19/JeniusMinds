@extends('layouts.app')

@section('title', 'Review Quiz: ' . $attempt->topic->topic_name)

@push('styles')
<style>
    .result-card { border-left-width: 5px; }
    .result-card-correct { border-left-color: #22c55e; } 
    .result-card-incorrect { border-left-color: #ef4444; } 
    .btn-gradient { background-image: linear-gradient(to right, #F7B5A3, #E99A87); transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); }
    .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 8px 15px -8px rgba(238, 169, 157, 0.6); filter: brightness(1.05); }
</style>
@endpush

@section('content')
<main class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">{{ $attempt->topic->topic_name }}</h1>
            <p class="text-xl text-gray-500">Review of your attempt on {{ $attempt->created_at->format('d M Y') }}</p>
            <p class="text-3xl font-bold text-green-600 mt-4">
                Final Score: {{ $attempt->score }}/{{ $attempt->total_questions }}
            </p>
        </div>

        <!-- ================== BAGIAN ULASAN JAWABAN ================== -->
        <div class="space-y-6">
            @foreach ($results as $index => $result)
                <div class="bg-white p-6 rounded-xl shadow-md result-card {{ $result['is_correct'] ? 'result-card-correct' : 'result-card-incorrect' }}">
                    <p class="font-bold text-lg text-gray-900">{{ $index + 1 }}. {{ $result['question_text'] }}</p>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        {{-- Jawaban Anda --}}
                        <div>
                            <p class="font-semibold text-gray-500 mb-1">Your Answer:</p>
                            <div class="flex items-start">
                                @if ($result['is_correct'])
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                @else
                                    <i class="fas fa-times-circle text-red-500 mr-2 mt-1"></i>
                                @endif
                                <span class="font-medium text-gray-700">
                                    {{ is_array($result['user_answer']) ? implode(', ', $result['user_answer']) : $result['user_answer'] }}
                                </span>
                            </div>
                        </div>
                        {{-- Kunci Jawaban --}}
                        <div class="{{ $result['is_correct'] ? 'opacity-50' : '' }}">
                            <p class="font-semibold text-gray-500 mb-1">Correct Answer:</p>
                            <div class="flex items-start">
                                <i class="fas fa-key text-yellow-500 mr-2 mt-1"></i>
                                <span class="font-medium text-gray-700">
                                    {{ is_array($result['correct_answer']) ? implode(', ', $result['correct_answer']) : $result['correct_answer'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- ================ AKHIR BAGIAN ULASAN ================ -->

        <div class="text-center mt-12">
            <a href="{{ route('profile.show') }}" class="bg-gray-200 text-gray-800 font-bold py-3 px-8 rounded-full text-lg hover:bg-gray-300 transition">Back to Profile</a>
        </div>
    </div>
</main>
@endsection
