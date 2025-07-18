<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Judul halaman akan dinamis, dengan judul default 'Jenius Minds' --}}
    <title>@yield('title', 'Jenius Minds')</title>
    
    {{-- Aset CSS dan Font --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Style kustom yang umum di seluruh situs --}}
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFAF3;
        }
        .brand-border { border-color: #F3EAE6; }
        .btn-gradient {
            background-image: linear-gradient(to right, #F7B5A3, #F7B5A3);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(238, 169, 157, 0.7);
        }
    </style>

    {{-- Memungkinkan halaman lain untuk menambahkan style tambahan --}}
    @stack('styles')
</head>
<body class="text-gray-700">

    <!-- ===== Bagian Header Umum ===== -->
    <header class="sticky top-0 bg-[#FFFAF3]/70 backdrop-blur-lg z-20 shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    <img src="{{ asset('assets/Logo.png') }}" alt="Logo Jenius Minds" class="w-14 h-14">
                    <span class="text-xl font-bold text-gray-800 group-hover:text-[#EEA99D] transition-colors">Jenius Minds</span>
                </a>
                
                <div class="hidden md:block w-1/3">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                        <input type="text" placeholder="Search for quizzes..." class="w-full pl-10 pr-4 py-2 border brand-border rounded-full focus:outline-none focus:ring-2 focus:ring-pink-300 transition-all">
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('signin') }}" class="text-gray-600 hover:text-[#EEA99D] font-medium transition-colors">Sign In</a>
                        <a href="{{ route('signup') }}" class="btn-gradient text-black px-6 py-2 rounded-full font-bold shadow-md">Sign Up</a>
                    @else
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="w-10 h-10 rounded-full overflow-hidden border-2 border-pink-200 hover:border-pink-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-400 transition">
                                <img class="w-full h-full object-cover" src="https://placehold.co/100x100/FEF6F3/EEA99D?text={{ substr(Auth::user()->name, 0, 1) }}" alt="Foto Profil">
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-30" style="display: none;">
                                <div class="py-1">
                                    <div class="px-4 py-3 border-b">
                                        <p class="text-sm text-gray-500">Signed in as</p>
                                        <p class="text-base font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                    </div>
                                    <a href="{{ route('profile.show') }}" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-user-circle w-5 mr-3 text-gray-500"></i> Profile</a>
                                    <form method="POST" action="{{ route('signout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600"><i class="fas fa-sign-out-alt w-5 mr-3 text-gray-500"></i> Sign Out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- ===== Konten Utama dari Halaman Child ===== -->
    <main>
        @yield('content')
    </main>


    <!-- ===== Bagian Footer Umum ===== -->
    <footer class="mt-20 bg-[#FFFAF3]">
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

    {{-- Memungkinkan halaman lain untuk menambahkan script tambahan --}}
    @stack('scripts')
</body>
</html>
