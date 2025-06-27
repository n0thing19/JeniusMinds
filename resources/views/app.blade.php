<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenius Minds - Homepage</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Icon Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Menggunakan font Poppins sebagai default */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFBF5;
        }
        /* Gradient dan Warna Kustom */
        .brand-pink-light { background-color: #FEF6F3; }
        .brand-pink-dark { background-color: #EEA99D; }
        .brand-blue-light { background-color: #EBF5FF; }
        .brand-yellow-light { background-color: #FFFBEB; }
        .brand-border { border-color: #F3EAE6; }

        .btn-gradient {
            background-image: linear-gradient(to right, #F5B9B0, #EEA99D);
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
    </style>
</head>
<body class="text-gray-700">

    <!-- ===== Bagian Header ===== -->
    <header class="sticky top-0 bg-white/70 backdrop-blur-lg z-10 shadow-sm">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="#" class="flex items-center space-x-3 group">
                    <img src="https://placehold.co/40x40/EEA99D/FFFFFF?text=JM" alt="Logo Jenius Minds" class="rounded-full group-hover:scale-110 transition-transform">
                    <span class="text-xl font-bold text-gray-800 group-hover:text-brand-pink-dark transition-colors">Jenius Minds</span>
                </a>

                <!-- Kolom Pencarian -->
                <div class="hidden md:block w-1/3">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" placeholder="Search for quizzes..." class="w-full pl-10 pr-4 py-2 border brand-border rounded-full focus:outline-none focus:ring-2 focus:ring-brand-pink-dark/50 transition-all">
                    </div>
                </div>

                <!-- Tombol Sign In & Sign Up -->
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('signin') }}" class="text-gray-600 hover:text-brand-pink-dark font-medium transition-colors">Sign In</a>
                        <a href="{{ route('signup') }}" class="btn-gradient text-white px-6 py-2 rounded-full font-bold shadow-md">Sign Up</a>
                        @endguest

                    @auth
                        <form action="{{ route('signout') }}" method="POST" class="flex items-center space-x-4">
                            @csrf
                            <button type="submit" class="btn-gradient text-white px-6 py-2 rounded-full font-bold shadow-md">Sign Out</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- ===== Bagian Main Content ===== -->
    <div class="container mx-auto px-6 py-4 mt-8">
        <main>
            <!-- Kolom Enter Code -->
            <section class="mb-12 text-center">
                <label for="enter-code" class="font-semibold text-lg text-gray-700">Have a code?</label>
                <div class="mt-2 flex justify-center">
                    <input type="text" id="enter-code" placeholder="Enter Code Here" class="w-full md:w-1/3 px-5 py-3 border-2 brand-border rounded-full focus:outline-none focus:ring-2 focus:ring-brand-pink-dark/50 transition-all shadow-sm">
                </div>
            </section>

            <!-- Card "Make A Quiz" -->
            <section class="brand-pink-light border brand-border rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between mb-16 card-hover-effect">
                <div class="flex items-center space-x-6 mb-6 md:mb-0">
                    <!-- Icon -->
                    <img src="https://placehold.co/120x120/FFFFFF/EEA99D?text=Q%2BA" alt="Ikon Kuis" class="rounded-lg shadow-lg">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-800">Make A Quiz</h2>
                        <p class="mt-2 text-gray-600 max-w-md">
                            Got some fun ideas in your head? Or want to share knowledge in an engaging way? Let's create a quiz! It'll be a blast, for sure.
                        </p>
                    </div>
                </div>
                <a href="#" class="btn-gradient text-white px-8 py-4 rounded-full font-bold shadow-lg text-lg whitespace-nowrap">
                    <i class="fas fa-pencil-alt mr-2"></i> QUIZ EDITOR
                </a>
            </section>
            
            <!-- Fungsi untuk membuat kartu topik -->
            <?php
            function topicCard($title, $icon, $colorClass) {
                return <<<HTML
                <div class="{$colorClass} p-6 border brand-border rounded-2xl text-center flex flex-col justify-center items-center h-48 relative card-hover-effect cursor-pointer">
                    <div class="icon-bg mb-3">
                        <i class="{$icon} text-lg {$colorClass}-text"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800">{$title}</h4>
                    <div class="absolute top-3 right-3 text-gray-300">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                </div>
                HTML;
            }
            ?>

            <!-- Kategori -->
            <div class="space-y-16">
                 <!-- Kategori Matematika -->
                <section>
                    <h3 class="text-3xl font-bold mb-6 flex items-center"><i class="fas fa-calculator mr-3 text-brand-pink-dark"></i> Mathematics</h3>
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="flex-shrink-0 w-full md:w-1/5 brand-yellow-light p-4 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                            <img src="https://placehold.co/200x200/FFFBEB/fbbf24?text=Math" alt="Ilustrasi Matematika" class="rounded-lg object-contain">
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 w-full">
                            <?= topicCard('Algebra', 'fas fa-square-root-alt', 'brand-yellow-light') ?>
                            <?= topicCard('Arithmetic', 'fas fa-sort-numeric-up', 'brand-yellow-light') ?>
                            <?= topicCard('Trigonometry', 'fas fa-wave-square', 'brand-yellow-light') ?>
                            <?= topicCard('Geometry', 'fas fa-draw-polygon', 'brand-yellow-light') ?>
                        </div>
                    </div>
                </section>
                
                <!-- Kategori Bahasa Inggris -->
                <section>
                    <h3 class="text-3xl font-bold mb-6 flex items-center"><i class="fas fa-book-open mr-3 text-brand-pink-dark"></i> English</h3>
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="flex-shrink-0 w-full md:w-1/5 brand-pink-light p-4 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                             <img src="https://placehold.co/200x200/FEF6F3/f472b6?text=English" alt="Ilustrasi Bahasa Inggris" class="rounded-lg object-contain">
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 w-full">
                            <?= topicCard('Vocabulary', 'fas fa-spell-check', 'brand-pink-light') ?>
                            <?= topicCard('Grammar', 'fas fa-pen-fancy', 'brand-pink-light') ?>
                            <?= topicCard('Tenses', 'fas fa-clock', 'brand-pink-light') ?>
                            <?= topicCard('Reading', 'fas fa-readme', 'brand-pink-light') ?>
                        </div>
                    </div>
                </section>
                
                <!-- Kategori Kimia -->
                <section>
                    <h3 class="text-3xl font-bold mb-6 flex items-center"><i class="fas fa-flask mr-3 text-brand-pink-dark"></i> Chemistry</h3>
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="flex-shrink-0 w-full md:w-1/5 brand-blue-light p-4 border brand-border rounded-2xl flex items-center justify-center card-hover-effect">
                             <img src="https://placehold.co/200x200/EBF5FF/60a5fa?text=Chemistry" alt="Ilustrasi Kimia" class="rounded-lg object-contain">
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 w-full">
                            <?= topicCard('Elements', 'fas fa-atom', 'brand-blue-light') ?>
                            <?= topicCard('Bonds', 'fas fa-link', 'brand-blue-light') ?>
                            <?= topicCard('Reactions', 'fas fa-burn', 'brand-blue-light') ?>
                            <?= topicCard('Acids', 'fas fa-vial', 'brand-blue-light') ?>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- ===== Bagian Footer ===== -->
    <footer class="bg-white mt-24 pt-16 pb-8 border-t-2 brand-border">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-10">
                <div class="col-span-2 md:col-span-1">
                     <a href="#" class="flex items-center space-x-3">
                        <img src="https://placehold.co/40x40/EEA99D/FFFFFF?text=JM" alt="Logo Jenius Minds" class="rounded-full">
                        <span class="text-xl font-bold text-gray-800">Jenius Minds</span>
                    </a>
                    <p class="text-gray-500 mt-3 text-sm">Making learning fun, one quiz at a time.</p>
                </div>
                <div>
                    <h5 class="font-bold mb-4 text-gray-800">About</h5>
                    <nav class="flex flex-col space-y-2 text-sm text-gray-500">
                        <a href="#" class="hover:text-brand-pink-dark hover:underline">About Us</a>
                        <a href="#" class="hover:text-brand-pink-dark hover:underline">How it works</a>
                        <a href="#" class="hover:text-brand-pink-dark hover:underline">Blog</a>
                        <a href="#" class="hover:text-brand-pink-dark hover:underline">Forum</a>
                    </nav>
                </div>
                <div>
                    <h5 class="font-bold mb-4 text-gray-800">Support</h5>
                    <nav class="flex flex-col space-y-2 text-sm text-gray-500">
                        <a href="#" class="hover:text-brand-pink-dark hover:underline">Help Center</a>
                        <a href="#" class="hover:text-brand-pink-dark hover:underline">Contact us</a>
                        <a href="#" class="hover:text-brand-pink-dark hover:underline">Privacy Policy</a>
                        <a href="#" class="hover:text-brand-pink-dark hover:underline">Terms of Service</a>
                    </nav>
                </div>
                <div class="col-span-2 md:col-span-2 flex flex-col space-y-4 items-start">
                    <a href="#" class="flex items-center bg-black text-white px-4 py-2 rounded-lg w-48 shadow-lg hover:bg-gray-800 transition-colors">
                        <i class="fab fa-apple text-2xl mr-3"></i>
                        <div>
                            <p class="text-xs">Download on the</p>
                            <p class="text-lg font-semibold">App Store</p>
                        </div>
                    </a>
                     <a href="#" class="flex items-center bg-black text-white px-4 py-2 rounded-lg w-48 shadow-lg hover:bg-gray-800 transition-colors">
                        <i class="fab fa-google-play text-xl mr-3"></i>
                        <div>
                            <p class="text-xs">GET IT ON</p>
                            <p class="text-lg font-semibold">Google Play</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t brand-border flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p class="mb-4 md:mb-0">&copy; 2025 Jenius Minds. All rights reserved.</p>
                <div class="flex space-x-5">
                    <a href="#" class="hover:text-brand-pink-dark transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                    <a href="#" class="hover:text-brand-pink-dark transition-colors"><i class="fab fa-facebook-f text-xl"></i></a>
                    <a href="#" class="hover:text-brand-pink-dark transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
