<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenius Minds - Homepage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Menggunakan font Poppins sebagai default */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFAF3;
        }

        .footer {
            background-color: #FFFAF3;
        }
        /* Gradient dan Warna Kustom */
        .brand-pink-light { background-color: #F4E0DA; }
        .brand-pink-dark { background-color: #EEA99D; }
        
        /* Warna Kartu untuk Setiap Kategori */
        .brand-yellow-card { background-color: #F5F5F5; } /* Mathematics, Physics */ 
        .brand-pink-card { background-color: #FFE1D6; }  /* English */
        .brand-blue-card { background-color: #C9E3F2; }   /* Chemistry */
        .brand-purple-card { background-color: #DEDDE8; } /* Computers */
        .brand-green-card { background-color: #F1F2E2; }  /* Biology */
        .brand-gray-card { background-color: #FEE394; }   /* Economy */
        .brand-teal-card { background-color: #EEF5F8; }   /* Geography */
        .brand-indigo-card { background-color: #FFFFFF; } /* Music */
        .brand-red-card { background-color: #F0EBCB; }    /* Sport */
        .brand-gold-card { background-color: #E3D4BE; }   /* Mandarin */

        .brand-border { border-color: #F3EAE6; }

        .btn-gradient {
            background-image: linear-gradient(to right, #F7B5A3, #F7B5A3);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(238, 169, 157, 0.7);
        }
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
        .scroll-container::-webkit-scrollbar {
            display: none; /* Menyembunyikan scrollbar untuk browser Webkit */
        }
        .category-container {
            position: relative;
            padding: 0 3rem; /* Memberi ruang untuk tombol scroll */
        }
        .scroll-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 10;
            cursor: pointer;
            border: none;
            outline: none;
        }
        .scroll-btn:hover {
            background: #f5f5f5;
        }
        .scroll-btn-left {
            left: 0;
        }
        .scroll-btn-right {
            right: 0;
        }
    </style>
</head>
<body class="text-gray-700">

    <!-- ===== Bagian Header ===== -->
    <header class="sticky top-0 bg-[#FFFAF3] bg-opacity/70 backdrop-blur-lg z-20 shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between items-center">
                <a href="#" class="flex items-center space-x-3 group">
                    <img src="{{ asset('assets/Logo.png') }}" alt="Logo Jenius Minds" class="w-14 h-14">
                    <span class="text-xl font-bold text-gray-800 group-hover:text-brand-pink-dark transition-colors">Jenius Minds</span>
                </a>
                
                <div class="hidden md:block w-1/3">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                        <input type="text" placeholder="Search for quizzes..." class="w-full pl-10 pr-4 py-2 border brand-border rounded-full focus:outline-none focus:ring-2 focus:ring-brand-pink-dark/50 transition-all">
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('signin') }}" class="text-gray-600 hover:brand-pink-dark font-medium transition-colors">Sign In</a>
                        <a href="{{ route('signup') }}" class="btn-gradient text-white px-6 py-2 rounded-full font-bold shadow-md">Sign Up</a>
                    @endguest

                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="w-10 h-10 rounded-full overflow-hidden border-2 border-pink-200 hover:border-pink-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-400 transition">
                                <img class="w-full h-full object-cover" src="https://placehold.co/100x100/FEF6F3/EEA99D?text={{ substr(Auth::user()->name, 0, 1) }}" alt="Foto Profil">
                            </button>

                            <div 
                                x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-30"
                                style="display: none;"
                            >
                                <div class="py-1">
                                    <div class="px-4 py-3 border-b border-gray-200">
                                        <p class="text-sm text-gray-500">Signed in as</p>
                                        <p class="text-base font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                    </div>
                                    
                                    <div class="py-1">
                                        <a href="{{-- route('profile.edit') --}}" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user-circle w-5 mr-3 text-gray-500"></i> Profile
                                        </a>
                                        <a href="#" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-cog w-5 mr-3 text-gray-500"></i> Settings
                                        </a>
                                    </div>

                                    <div class="py-1 border-t border-gray-200">
                                        <form method="POST" action="{{ route('signout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">
                                                <i class="fas fa-sign-out-alt w-5 mr-3 text-gray-500"></i> Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- ===== Bagian Main Content ===== -->
    <div class="container mx-auto px-6 py-4 mt-4">

        <main>
            <section class="mb-10 text-left">
                <label for="enter-code" class="font-semibold text-lg text-gray-700">Have a code?</label>
                <div class="mt-2 flex justify-left">
                    <input type="text" id="enter-code" placeholder="Enter Code Here" class="w-full md:w-1/3 px-5 py-3 border-2 brand-border rounded-full focus:outline-none focus:ring-2 focus:ring-brand-pink-dark/50 transition-all shadow-sm">
                </div>
            </section>

            <section class="brand-pink-light border brand-border rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between mb-12 card-hover-effect">
                <div class="flex items-center space-x-6 mb-6 md:mb-0">
                    <img src="{{ asset('assets/IconQuiz.png') }}" alt="Ikon Kuis" class="w-42 h-48">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-800">Make A Quiz</h2>
                        <p class="mt-2 text-gray-600 max-w-md">Got some fun ideas in your head? Or want to share knowledge in an engaging way? Let's create a quiz! It'll be a blast, for sure.</p>
                    </div>
                </div>
                <a href="#" class="btn-gradient text-black px-8 py-4 rounded-full font-bold shadow-lg text-lg whitespace-nowrap"><i class="fas fa-pencil-alt mr-2"></i> QUIZ EDITOR</a>
            </section>
            
            <?php
            function topicCard($title, $icon, $colorClass) {
                return <<<HTML
                <div class="{$colorClass} p-6 border brand-border rounded-2xl text-center flex flex-col justify-center items-center h-48 relative card-hover-effect cursor-pointer">
                    <div class="icon-bg mb-3"><i class="{$icon} text-lg"></i></div>
                    <h4 class="text-xl font-bold text-gray-800">{$title}</h4>
                </div>
                HTML;
            }
            ?>

            <div class="space-y-16">

            <div class="category-container">
                <section>
                    <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-calculator mr-3 text-gray-600"></i> Mathematics</h3>
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                        <div class="flex-shrink-0 w-64 h-48 brand-yellow-card border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                            <img src="{{ asset('assets/math.png') }}" alt="Ilustrasi Matematika" class="max-h-full object-contain">
                        </div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Algebra', 'fas fa-square-root-alt', 'brand-yellow-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Arithmetic', 'fas fa-sort-numeric-up', 'brand-yellow-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Trigonometry', 'fas fa-wave-square', 'brand-yellow-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Geometry', 'fas fa-draw-polygon', 'brand-yellow-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Calculus', 'fas fa-infinity', 'brand-yellow-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Statistics', 'fas fa-chart-pie', 'brand-yellow-card') ?></div>
                    </div>
                </section>
                <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
                <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
            </div>

            <div class="category-container">
                <section>
                    <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-book-open mr-3 text-gray-600"></i> English</h3>
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                        <div class="flex-shrink-0 w-64 h-48 brand-pink-card p-6 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                            <img src="{{ asset('assets/english.png') }}" alt="Ilustrasi English" class="max-h-full object-contain">
                        </div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Vocabulary', 'fas fa-spell-check', 'brand-pink-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Grammar', 'fas fa-pen-fancy', 'brand-pink-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Reading', 'fas fa-readme', 'brand-pink-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Speaking', 'fas fa-microphone-alt', 'brand-pink-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Writing', 'fas fa-pencil-alt', 'brand-pink-card') ?></div>
                        <div class="flex-shrink-0 w-64"><?= topicCard('Listening', 'fas fa-headphones', 'brand-pink-card') ?></div>
                    </div>
                </section>
                <button class="scroll-btn scroll-btn-left"><i class="fas fa-chevron-left text-gray-600"></i></button>
                <button class="scroll-btn scroll-btn-right"><i class="fas fa-chevron-right text-gray-600"></i></button>
            </div>

            <div class="category-container">
                <section>
                    <h3 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-flask mr-3 text-gray-600"></i> Chemistry</h3>
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
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
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
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
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
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
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
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
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
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
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
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
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
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
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
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
                    <div class="scroll-container flex overflow-x-auto gap-6 pb-4 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
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
        </main>
    </div>

    <footer class="mt-20">
        <!-- Bagian utama footer dengan container -->
        <div class="pb-6">
            <div class="container mx-auto px-4 sm:px-6 lg:px-20">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
                    <!-- Logo -->
                    <div class="col-span-2 md:col-span-1">
                        <a href="/" class="flex items-center space-x-3 mb-4">
                            <img src="{{ asset('assets/Logo.png') }}" alt="Logo Jenius Minds" class="w-10 h-10">
                            <span class="text-xl font-bold text-black">Jenius Minds</span>
                        </a>
                    </div>

                    <!-- About -->
                    <div>
                        <h5 class="font-bold mb-4 text-[#CC6C4F]">About</h5>
                        <nav class="flex flex-col space-y-2 text-sm text-gray-500">
                            <a href="/about" class="hover:text-[#CC6C4F] hover:underline">About Jenius Minds</a>
                            <a href="/how-it-works" class="hover:text-[#CC6C4F] hover:underline">How it works</a>
                            <a href="/blog" class="hover:text-[#CC6C4F] hover:underline">Blog</a>
                            <a href="/forum" class="hover:text-[#CC6C4F] hover:underline">Forum</a>
                        </nav>
                    </div>

                    <!-- Support -->
                    <div>
                        <h5 class="font-bold mb-4 text-[#CC6C4F]">Support</h5>
                        <nav class="flex flex-col space-y-2 text-sm text-gray-500">
                            <a href="/help" class="hover:text-[#CC6C4F] hover:underline">Help Center</a>
                            <a href="/contact" class="hover:text-[#CC6C4F] hover:underline">Contact us</a>
                            <a href="/privacy" class="hover:text-[#CC6C4F] hover:underline">Privacy policy</a>
                            <a href="/terms" class="hover:text-[#CC6C4F] hover:underline">Terms of service</a>
                        </nav>
                    </div>

                    <!-- Download Buttons -->
                    <div class="flex flex-col space-y-4">
                        <a href="https://www.apple.com/app-store/" target="_blank" rel="noopener noreferrer" class="flex items-center bg-black text-white px-4 py-2 rounded-xl w-full md:w-52 shadow-lg hover:bg-gray-800 transition-all duration-300">
                            <i class="fab fa-apple text-xl mr-3"></i>
                            <div class="leading-tight">
                                <p class="text-xs mb-0.5">Download on the</p>
                                <p class="text-lg font-bold">App Store</p>
                            </div>
                        </a>
                        
                        <a href="https://play.google.com/store/apps" target="_blank" rel="noopener noreferrer" class="flex items-center bg-black text-white px-4 py-2 rounded-xl w-full md:w-52 shadow-lg hover:bg-gray-800 transition-all duration-300">
                            <i class="fab fa-google-play text-xl mr-3"></i>
                            <div class="leading-tight">
                                <p class="text-xs mb-0.5">GET IT ON</p>
                                <p class="text-lg font-bold">Google Play</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian copyright -->
        <div class="border-t-2 border-[#F3EAE6]">
            <div class="container mx-auto px-4 sm:px-6 lg:px-20 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                    <p class="mb-4 md:mb-0">&copy; 2025 Jenius Minds</p>
                    <div class="flex space-x-5">
                        <a href="https://twitter.com/jeniusminds" target="_blank" rel="noopener noreferrer" class="hover:text-[#CC6C4F] transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="https://facebook.com/jeniusminds" target="_blank" rel="noopener noreferrer" class="hover:text-[#CC6C4F] transition-colors"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="https://instagram.com/jeniusminds" target="_blank" rel="noopener noreferrer" class="hover:text-[#CC6C4F] transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryContainers = document.querySelectorAll('.category-container');

            categoryContainers.forEach(container => {
                const scrollContainer = container.querySelector('.scroll-container');
                const leftBtn = container.querySelector('.scroll-btn-left');
                const rightBtn = container.querySelector('.scroll-btn-right');

                if (scrollContainer && leftBtn && rightBtn) {
                    const scrollAmount = 256 + 24; // Width of card + gap

                    rightBtn.addEventListener('click', () => {
                        scrollContainer.scrollBy({
                            left: scrollAmount,
                            behavior: 'smooth'
                        });
                    });

                    leftBtn.addEventListener('click', () => {
                        scrollContainer.scrollBy({
                            left: -scrollAmount,
                            behavior: 'smooth'
                        });
                    });

                    // Hide left button initially
                    leftBtn.style.display = 'none';

                    // Show/hide buttons based on scroll position
                    scrollContainer.addEventListener('scroll', () => {
                        const { scrollLeft, scrollWidth, clientWidth } = scrollContainer;
                        
                        // Show/hide left button
                        if (scrollLeft > 0) {
                            leftBtn.style.display = 'flex';
                        } else {
                            leftBtn.style.display = 'none';
                        }

                        // Show/hide right button
                        if (scrollLeft < scrollWidth - clientWidth - 1) {
                            rightBtn.style.display = 'flex';
                        } else {
                            rightBtn.style.display = 'none';
                        }
                    });
                }
            });
        });
    </script>

</body>
</html>