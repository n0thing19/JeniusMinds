<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions - Jenius Minds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFBF5;
        }
        .brand-pink-light { background-color: #FDECE7; }
        .brand-pink-dark-bg { background-color: #F5B9B0; }
        .brand-border { border-color: #F3EAE6; }

        .question-type-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 2px solid transparent;
        }
        .question-type-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px -10px rgba(238, 169, 157, 0.3);
        }
        
        .icon-box {
            width: 4rem; /* 64px */
            height: 4rem; /* 64px */
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            border-radius: 0.75rem; /* 12px */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }
        
        .icon-box .fa-square, .icon-box .fa-check-square {
            font-size: 2rem; /* 32px */
            color: #F5B9B0;
        }
        .icon-box .fa-layer-group {
            font-size: 1.8rem;
            color: #F5B9B0;
        }
         .icon-box .custom-icon-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: #F5B9B0;
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

    <!-- ===== Konten Utama ===== -->
    <main class="container mx-auto px-6 py-16">
        
        <h1 class="text-center text-4xl font-extrabold text-gray-800 mb-12">ADD QUESTIONS</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <a href={{ route('quizaddbutton') }}>
            <!-- Tombol -->
            <div class="question-type-card bg-brand-pink-light p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                        <i class="far fa-square"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">BUTTONS</h2>
                        <p class="text-gray-500">One correct answer</p>
                    </div>
                </div>
            </a>
            <!-- Checkbox -->
            <a href={{ route('quizaddcheckbox') }}>
            <div class="question-type-card bg-brand-pink-light p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <i class="far fa-check-square"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">CHECKBOXES</h2>
                    <p class="text-gray-500">Multiple correct answers</p>
                </div>
            </div>
            </a>
            <!-- Reorder -->
            <a href={{ route('quizaddreorder') }}>
            <div class="question-type-card bg-brand-pink-light p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <i class="fas fa-layer-group"></i>
                 </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">REORDER</h2>
                    <p class="text-gray-500">Place answers in the correct order</p>
                </div>
            </div>
            </a>
            <!-- Type Answer -->
            <a href={{ route('quiztypeanswer') }}>
            <div class="question-type-card bg-brand-pink-light p-8 rounded-2xl flex items-center space-x-6 cursor-pointer">
                <div class="icon-box">
                    <span class="custom-icon-text">Aa</span>
                 </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">TYPE ANSWER</h2>
                    <p class="text-gray-500">Type the correct answer</p>
                </div>
            </div>
            </a>

        </div>
        
    </main>

</body>
</html>
