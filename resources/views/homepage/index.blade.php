@extends('layouts.app')

@section('title', 'Jenius Minds - Homepage')

@push('styles')
<style>
    /* Style khusus untuk halaman index */
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

    .card-hover-effect {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -15px rgba(0, 0, 0, 0.1);
    }
    .icon-bg {
        background: #FFFFFF;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px -4px rgba(0,0,0,0.1);
    }
    .scroll-container::-webkit-scrollbar { display: none; }
    .category-container { position: relative; padding: 0 3rem; }
    .scroll-btn {
        position: absolute; top: 60%; transform: translateY(-50%);
        background: white; border-radius: 50%; width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 10; cursor: pointer;
        border: none;
    }
    .scroll-btn:hover { background: #f5f5f5; }
    .scroll-btn-left { left: 0; }
    .scroll-btn-right { right: 0; }
</style>
@endpush

@section('content')
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
    
    @php
    function topicCard($title, $icon, $colorClass) {
        return <<<HTML
        <div class="{$colorClass} p-6 border brand-border rounded-2xl text-center flex flex-col justify-center items-center h-48 relative card-hover-effect cursor-pointer">
            <div class="icon-bg mb-3"><i class="{$icon} text-lg"></i></div>
            <h4 class="text-xl font-bold text-gray-800">{$title}</h4>
        </div>
        HTML;
    }
    @endphp

    <div class="space-y-16">

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-calculator mr-3 text-gray-600"></i> Mathematics</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-yellow-card border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/math.png') }}" alt="Ilustrasi Matematika" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Algebra', 'fas fa-square-root-alt', 'brand-yellow-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Arithmetic', 'fas fa-sort-numeric-up', 'brand-yellow-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Trigonometry', 'fas fa-wave-square', 'brand-yellow-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Geometry', 'fas fa-draw-polygon', 'brand-yellow-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Calculus', 'fas fa-infinity', 'brand-yellow-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Statistics', 'fas fa-chart-pie', 'brand-yellow-card') !!}</div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-book-open mr-3 text-gray-600"></i> English</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-pink-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/english.png') }}" alt="Ilustrasi English" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Vocabulary', 'fas fa-spell-check', 'brand-pink-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Grammar', 'fas fa-pen-fancy', 'brand-pink-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Reading', 'fas fa-readme', 'brand-pink-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Speaking', 'fas fa-microphone-alt', 'brand-pink-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Writing', 'fas fa-pencil-alt', 'brand-pink-card') !!}</div>
                    <div class="flex-shrink-0 w-64">{!! topicCard('Listening', 'fas fa-headphones', 'brand-pink-card') !!}</div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-flask mr-3 text-gray-600"></i> Chemistry</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-blue-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/chemistry.png') }}" alt="Ilustrasi Chemistry" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Elements', 'fas fa-atom', 'brand-blue-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Reactions', 'fas fa-burn', 'brand-blue-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Acids & Bases', 'fas fa-vial', 'brand-blue-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Organic', 'fas fa-seedling', 'brand-blue-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Stoichiometry', 'fas fa-balance-scale', 'brand-blue-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Thermochem', 'fas fa-thermometer-half', 'brand-blue-card') ?></div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-desktop mr-3 text-gray-600"></i> Computers</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-purple-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/computer.png') }}" alt="Ilustrasi Computers" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Programming', 'fas fa-code', 'brand-purple-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Hardware', 'fas fa-server', 'brand-purple-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Networking', 'fas fa-network-wired', 'brand-purple-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('AI', 'fas fa-robot', 'brand-purple-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Databases', 'fas fa-database', 'brand-purple-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Cybersecurity', 'fas fa-shield-alt', 'brand-purple-card') ?></div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-dna mr-3 text-gray-600"></i> Biology</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-green-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/biology.png') }}" alt="Ilustrasi Biology" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Cells', 'fas fa-microscope', 'brand-green-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Genetics', 'fas fa-dna', 'brand-green-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Anatomy', 'fas fa-user-md', 'brand-green-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Botany', 'fas fa-leaf', 'brand-green-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Ecology', 'fas fa-globe-africa', 'brand-green-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Zoology', 'fas fa-paw', 'brand-green-card') ?></div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-chart-line mr-3 text-gray-600"></i> Economy</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-gray-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/economy.png') }}" alt="Ilustrasi Economy" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Micro', 'fas fa-search-dollar', 'brand-gray-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Macro', 'fas fa-globe-americas', 'brand-gray-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Finance', 'fas fa-wallet', 'brand-gray-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Investing', 'fas fa-piggy-bank', 'brand-gray-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Trade', 'fas fa-exchange-alt', 'brand-gray-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Markets', 'fas fa-store', 'brand-gray-card') ?></div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-globe-asia mr-3 text-gray-600"></i> Geography</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-teal-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/geography.png') }}" alt="Ilustrasi Geography" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Maps', 'fas fa-map-marked-alt', 'brand-teal-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Countries', 'fas fa-flag', 'brand-teal-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Physical', 'fas fa-mountain', 'brand-teal-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Human', 'fas fa-users', 'brand-teal-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Climate', 'fas fa-cloud-sun-rain', 'brand-teal-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Oceans', 'fas fa-water', 'brand-teal-card') ?></div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-atom mr-3 text-gray-600"></i> Physics</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-yellow-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/physics.png') }}" alt="Ilustrasi Physics" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Mechanics', 'fas fa-cogs', 'brand-yellow-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Electricity', 'fas fa-bolt', 'brand-yellow-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Optics', 'fas fa-eye', 'brand-yellow-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Waves', 'fas fa-wave-square', 'brand-yellow-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Relativity', 'fas fa-rocket', 'brand-yellow-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Quantum', 'fas fa-project-diagram', 'brand-yellow-card') ?></div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-music mr-3 text-gray-600"></i> Music</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-indigo-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/music.png') }}" alt="Ilustrasi Music" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Theory', 'fas fa-book', 'brand-indigo-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Instruments', 'fas fa-guitar', 'brand-indigo-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('History', 'fas fa-history', 'brand-indigo-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Composition', 'fas fa-pen-alt', 'brand-indigo-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Production', 'fas fa-sliders-h', 'brand-indigo-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Vocal', 'fas fa-microphone', 'brand-indigo-card') ?></div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-futbol mr-3 text-gray-600"></i> Sports</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-red-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/sport.png') }}" alt="Ilustrasi Sports" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Soccer', 'fas fa-futbol', 'brand-red-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Basketball', 'fas fa-basketball-ball', 'brand-red-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Tennis', 'fas fa-table-tennis', 'brand-red-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Athletics', 'fas fa-running', 'brand-red-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Swimming', 'fas fa-swimmer', 'brand-red-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Strategy', 'fas fa-chess-board', 'brand-red-card') ?></div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

        <div class="category-container">
            <section>
                <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-language mr-3 text-gray-600"></i> Mandarin</h3>
                <div class="scroll-container flex overflow-x-auto gap-6 py-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="flex-shrink-0 w-64 h-48 brand-gold-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                        <img src="{{ asset('assets/mandarin.png') }}" alt="Ilustrasi Mandarin" class="max-h-full object-contain">
                    </div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Characters', 'fas fa-pen-alt', 'brand-gold-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Pinyin', 'fas fa-assistive-listening-systems', 'brand-gold-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Grammar', 'fas fa-stream', 'brand-gold-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Conversation', 'fas fa-comments', 'brand-gold-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Writing', 'fas fa-edit', 'brand-gold-card') ?></div>
                    <div class="flex-shrink-0 w-64"><?= topicCard('Culture', 'fas fa-landmark', 'brand-gold-card') ?></div>
                </div>
            </section>
            <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
            <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryContainers = document.querySelectorAll('.category-container');

        categoryContainers.forEach(container => {
            const scrollContainer = container.querySelector('.scroll-container');
            const leftBtn = container.querySelector('.scroll-btn-left');
            const rightBtn = container.querySelector('.scroll-btn-right');

            if (scrollContainer && leftBtn && rightBtn) {
                const scrollAmount = 256 + 24; // Lebar kartu + celah

                // Cek apakah perlu menampilkan tombol scroll
                const updateButtons = () => {
                    const { scrollLeft, scrollWidth, clientWidth } = scrollContainer;
                    
                    if (scrollWidth > clientWidth) {
                         leftBtn.style.display = scrollLeft > 0 ? 'flex' : 'none';
                         rightBtn.style.display = scrollLeft < scrollWidth - clientWidth - 1 ? 'flex' : 'none';
                    } else {
                        leftBtn.style.display = 'none';
                        rightBtn.style.display = 'none';
                    }
                };

                rightBtn.addEventListener('click', () => {
                    scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                });

                leftBtn.addEventListener('click', () => {
                    scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                });

                scrollContainer.addEventListener('scroll', updateButtons);
                window.addEventListener('resize', updateButtons); // Perbarui saat ukuran window berubah
                
                updateButtons(); // Panggil saat pertama kali load
            }
        });
    });
</script>
@endpush
