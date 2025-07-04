<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Editor - Jenius Minds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFBF5;
            padding-bottom: 100px; /* Ruang untuk footer navigasi */
        }
        .brand-pink-light { background-color: #FDECE7; }
        .brand-pink-dark-bg { background-color: #F5B9B0; }
        .brand-border { border-color: #F3EAE6; }

        .question-nav-btn {
            transition: all 0.2s ease;
        }
        .question-nav-btn.active {
            background-color: #F5B9B0;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px -2px rgba(238, 169, 157, 0.5);
        }
    </style>
</head>
<body class="text-gray-700">

    <!-- ===== Bagian Header ===== -->
    <header class="bg-[#FFFBF5] z-20">
        <div class="container mx-auto px-6 py-3 border-b-2 brand-border">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center space-x-3 group">
                    <img src="https://placehold.co/56x56/F4E0DA/000?text=JM" alt="Logo Jenius Minds" class="w-14 h-14 rounded-full">
                    <span class="text-xl font-bold text-gray-800 group-hover:text-pink-500 transition-colors">Jenius Minds</span>
                </a>
                <div class="hidden md:block w-1/3">
                    <input type="text" placeholder="Search" class="w-full px-4 py-2 border-2 brand-border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-300 transition-all">
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-gray-600 hover:text-pink-500 font-medium transition-colors">Sign In</a>
                    <a href="#" class="bg-brand-pink-dark-bg text-white px-6 py-2 rounded-lg font-bold shadow-sm hover:brightness-105 transition">Sign Up</a>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== Konten Editor Utama ===== -->
    <main class="container mx-auto px-6 py-8">
        
        <!-- Kotak Pertanyaan -->
        <div class="bg-brand-pink-light p-6 rounded-2xl shadow-lg mb-8">
            <textarea 
                rows="3" 
                placeholder="Type Question Here" 
                class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
            >Type Question Here</textarea>
        </div>

        <!-- Kotak Jawaban -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border-2 brand-border">
            <textarea 
                rows="6" 
                placeholder="Type Answer Here" 
                class="w-full bg-transparent text-xl font-semibold placeholder:text-gray-500/80 focus:outline-none resize-none"
            >Type Answer Here</textarea>
        </div>
        
    </main>

    <!-- ===== Navigasi Bawah ===== -->
    <footer class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-sm p-4 border-t brand-border">
        <div class="container mx-auto flex items-center justify-center space-x-2">
            <button class="question-nav-btn bg-gray-200 px-5 py-3 rounded-lg font-bold">Settings</button>
            <button class="question-nav-btn bg-brand-pink-light w-12 h-12 rounded-lg font-bold active">1</button>
            <button class="question-nav-btn bg-brand-pink-light w-12 h-12 rounded-lg font-bold">2</button>
            <button class="question-nav-btn bg-brand-pink-light w-12 h-12 rounded-lg font-bold">3</button>
            <button class="question-nav-btn bg-brand-pink-light w-12 h-12 rounded-lg font-bold">4</button>
            <button class="question-nav-btn bg-brand-pink-light w-12 h-12 rounded-lg font-bold">5</button>
            <button class="question-nav-btn bg-brand-pink-light w-12 h-12 rounded-lg font-bold">6</button>
            <button class="question-nav-btn bg-brand-pink-light w-12 h-12 rounded-lg font-bold">7</button>
            <button class="bg-gray-800 text-white w-12 h-12 rounded-lg font-bold text-2xl hover:bg-gray-700 transition-transform hover:scale-105">+</button>
        </div>
    </footer>

</body>
</html>
