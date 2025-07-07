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
            rows="3" 
            placeholder="Type Question Here" 
            class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
        >Type Question Here</textarea>
    </div>

    <!-- Kotak Jawaban -->
    <div class="bg-white p-12 rounded-xl shadow-lg brand-border border-2">
        <textarea 
            rows="5" 
            placeholder="Type Answer Here" 
            class="w-full bg-transparent text-xl font-semibold placeholder:text-gray-500/80 focus:outline-none resize-none"
        >Type Answer Here</textarea>
    </div>
    
</div>
@endsection
