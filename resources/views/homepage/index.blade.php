@extends('layouts.app')

@section('title', 'Jenius Minds - Homepage')

@push('styles')
<style>
    body { font-family: 'Poppins', sans-serif; background-color: #FFFAF3; }
    body.modal-open { overflow: hidden; }
    .brand-pink-light { background-color: #F4E0DA; }
    .brand-pink-dark { background-color: #EEA99D; }
    .brand-yellow-card { background-color: #F5F5F5; } 
    .brand-pink-card { background-color: #FFE1D6; }
    .brand-blue-card { background-color: #C9E3F2; }
    .brand-purple-card { background-color: #DEDDE8; }
    .brand-green-card { background-color: #F1F2E2; }
    .brand-gray-card { background-color: #FEE394; }
    .brand-teal-card { background-color: #EEF5F8; }
    .brand-indigo-card { background-color: #FFFFFF; }
    .brand-red-card { background-color: #F0EBCB; }
    .brand-gold-card { background-color: #E3D4BE; }
    .card-hover-effect { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover-effect:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -15px rgba(0, 0, 0, 0.1); }
    .icon-bg { background: #FFFFFF; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px -4px rgba(0,0,0,0.1); }
    .scroll-container::-webkit-scrollbar { display: none; }
    .category-container { position: relative; padding: 0 3rem; }
    .scroll-btn { position: absolute; top: 50%; transform: translateY(-50%); background: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 10; cursor: pointer; border: none; }
    .scroll-btn:hover { background: #f5f5f5; }
    .scroll-btn-left { left: 0; }
    .scroll-btn-right { right: 0; }
    .btn-gradient { background-image: linear-gradient(to right, #F7B5A3, #E99A87); transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); }
    .btn-gradient:hover { transform: translateY(-3px) scale(1.05); box-shadow: 0 12px 25px -8px rgba(238, 169, 157, 0.6); filter: brightness(1.1); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
{{-- 
  PERBAIKAN: Logika Alpine.js ditempatkan langsung di dalam atribut x-data.
  Ini adalah cara yang paling andal untuk memastikan semuanya berfungsi.
--}}
<div x-data="{
    isQuizModalOpen: false,
    selectedQuiz: {},
    openQuizModal(quizData) {
        this.selectedQuiz = quizData;
        this.isQuizModalOpen = true;
        document.body.classList.add('modal-open');
    },
    closeModal() {
        this.isQuizModalOpen = false;
        document.body.classList.remove('modal-open');
    }
}">
    <div class="container mx-auto px-6 py-4 mt-4">
        <section class="mb-10 text-left">
            <label for="enter-code" class="font-semibold text-lg text-gray-700">Have a code?</label>
            <div class="mt-2 flex justify-left">
                <input type="text" id="enter-code" placeholder="Enter Code Here" class="w-full md:w-1/3 px-5 py-3 border-2 brand-border rounded-full focus:outline-none focus:ring-2 focus:ring-[#EEA99D]/50 transition-all shadow-sm">
            </div>
        </section>

        <section class="bg-[#F4E0DA] border brand-border rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between mb-12 card-hover-effect">
            <div class="flex items-center space-x-6 mb-6 md:mb-0">
                <img src="{{ asset('assets/IconQuiz.png') }}" alt="Ikon Kuis" class="w-42 h-48">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-800">Make A Quiz</h2>
                    <p class="mt-2 text-gray-600 max-w-md">Got some fun ideas in your head? Or want to share knowledge in an engaging way? Let's create a quiz! It'll be a blast, for sure.</p>
                </div>
            </div>
            <a href="{{ route('quiz.editor') }}" class="btn-gradient text-black px-8 py-4 rounded-full font-bold shadow-lg text-lg whitespace-nowrap"><i class="fas fa-pencil-alt mr-2"></i> QUIZ EDITOR</a>
        </section>
        
        <div class="space-y-16">
            @foreach ($subjects as $subject)
                <div class="category-container">
                    <section>
                        <h3 class="text-2xl font-bold mb-6 flex items-center">
                            <i class="{{ $subject->icon_class ?? 'fas fa-question-circle' }} mr-3 text-gray-600"></i> 
                            {{ $subject->subject_name }}
                        </h3>
                        <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth">
                            <div class="flex-shrink-0 w-64 h-48 {{ $subject->color_class ?? 'brand-yellow-card' }} border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                                <img src="{{ asset($subject->image_path ?? 'assets/default.png') }}" alt="Ilustrasi {{ $subject->subject_name }}" class="max-h-full object-contain p-4">
                            </div>
                            
                            @foreach ($subject->topics as $topic)
                                @php
                                    $modalData = $topic->modal_data ?? [];
                                @endphp
                                <div @click="openQuizModal({{ json_encode($modalData) }})" class="flex-shrink-0 w-64 p-6 border brand-border rounded-2xl text-center flex flex-col justify-center items-center h-48 relative card-hover-effect cursor-pointer {{ $subject->color_class ?? 'brand-yellow-card' }}">
                                    <div class="icon-bg mb-3"><i class="{{ $topic->icon_class ?? 'fas fa-star' }} text-lg"></i></div>
                                    <h4 class="text-xl font-bold text-gray-800">{{ $topic->topic_name }}</h4>
                                    <p class="text-sm text-gray-500 mt-2">{{ $topic->questions_count }} Questions</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                    <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
                    <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ===== Modal Kuis ===== -->
    <div x-show="isQuizModalOpen" x-transition class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-60 p-4" x-cloak>
        <div @click.away="closeModal()" class="bg-white rounded-2xl shadow-2xl w-full max-w-md text-center overflow-hidden" x-transition>
            <button @click="closeModal()" class="absolute top-4 right-4 text-white/70 hover:text-white text-3xl z-10">&times;</button>
            <img :src="selectedQuiz.image_url" alt="Gambar Kuis" class="w-full h-56 object-cover">
            <div class="p-8">
                <h2 class="text-3xl font-extrabold text-gray-800" x-text="selectedQuiz.subject_name"></h2>
                <p class="text-lg text-gray-500 mt-1" x-text="selectedQuiz.topic_name"></p>
                <div class="flex justify-center items-center space-x-4 my-6">
                    <div class="inline-flex items-center bg-pink-100 text-pink-700 font-semibold px-4 py-2 rounded-full text-sm">
                        <i class="fas fa-list-ol mr-2"></i>
                        <span x-text="selectedQuiz.question_count"></span> Questions
                    </div>
                    <div class="inline-flex items-center bg-blue-100 text-blue-700 font-semibold px-4 py-2 rounded-full text-sm">
                        <i class="fas fa-clock mr-2"></i>
                        <span x-text="`${Math.ceil(selectedQuiz.question_count * 0.5)}`"></span> Minutes
                    </div>
                </div>
                <a :href="`/quiz/start/${selectedQuiz.topic_name}`" class="block w-full btn-gradient text-white font-bold py-4 px-4 rounded-xl shadow-lg text-xl">
                <i class="fas fa-play mr-2"></i> START QUIZ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Skrip untuk tombol scroll kategori tetap bisa di sini karena tidak bergantung pada Alpine.js --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryContainers = document.querySelectorAll('.category-container');
        categoryContainers.forEach(container => {
            const scrollContainer = container.querySelector('.scroll-container');
            const leftBtn = container.querySelector('.scroll-btn-left');
            const rightBtn = container.querySelector('.scroll-btn-right');

            if (scrollContainer && leftBtn && rightBtn) {
                const scrollAmount = 280;
                const updateButtons = () => {
                    const { scrollLeft, scrollWidth, clientWidth } = scrollContainer;
                    if (scrollWidth > clientWidth) {
                         leftBtn.style.display = scrollLeft > 10 ? 'flex' : 'none';
                         rightBtn.style.display = scrollLeft < scrollWidth - clientWidth - 10 ? 'flex' : 'none';
                    } else {
                        leftBtn.style.display = 'none';
                        rightBtn.style.display = 'none';
                    }
                };
                rightBtn.addEventListener('click', () => scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' }));
                leftBtn.addEventListener('click', () => scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
                scrollContainer.addEventListener('scroll', updateButtons);
                window.addEventListener('resize', updateButtons);
                updateButtons();
            }
        });
    });
</script>
@endpush
