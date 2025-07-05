<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
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
        }
        .brand-pink-light { background-color: #FFFBF5; }
        .brand-pink-dark-bg { background-color: #F5B9B0; }
        .brand-border { border-color: #F3EAE6; }

        /* --- Answer Card Styles --- */
        .answer-card {
            background-color: #FFE2D6;
            border: 3px solid transparent;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        /* --- Custom Checkbox Styles --- */
        .custom-checkbox {
            width: 28px;
            height: 28px;
            border: 3px solid rgb(248, 132, 100);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .custom-checkbox i {
            display: none; /* Hide icon by default */
        }
        
        /* --- Selected State Styles --- */
        .answer-card.selected {
            border-color:rgb(224, 126, 65);
            background-color:rgb(240, 207, 187);
        }

        .answer-card.selected .custom-checkbox {
            background-color: rgb(228, 167, 129);
            border-color: rgb(224, 126, 65);
        }

        .answer-card.selected .custom-checkbox i {
            display: block; /* Show icon when selected */
        }


        /* --- Footer Button Styles --- */
        .question-nav-btn {
            transition: all 0.2s ease;
            flex-shrink: 0;
            font-weight: 700;
            border-radius: 0.75rem;
            border: 2px solid #F3EAE6;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .question-nav-btn.active {
            background-color: #FFE3D6;
            color: black;
            border-color: #F7B5A3;
        }

        /* --- Scrollbar Utility --- */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="h-full text-gray-700">

    <!-- Main application wrapper for layout -->
    <div class="flex flex-col h-full">

        <!-- ===== Bagian Header (Fixed) ===== -->
        <header class="bg-[#FFFBF5] z-20 flex-shrink-0">
            <div class="container mx-auto px-6 py-3 border-b-2 brand-border">
                <div class="flex justify-between items-center">
                    <a href="/" class="flex items-center space-x-3 group">
                        <img src="https://placehold.co/56x56/F4E0DA/000?text=JM" alt="Logo Jenius Minds" class="w-14 h-14 rounded-full">
                        <span class="text-xl font-bold text-gray-800 group-hover:text-pink-500 transition-colors">Jenius Minds</span>
                    </a>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="text-gray-600 hover:text-pink-500 font-medium transition-colors">Sign In</a>
                        <a href="#" class="bg-brand-pink-dark-bg text-white px-6 py-2 rounded-lg font-bold shadow-sm hover:brightness-105 transition">Sign Up</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- ===== Konten Editor Utama (Scrollable) ===== -->
        <main class="flex-grow overflow-y-auto">
            <div class="container mx-auto px-8 py-16">
                <!-- Kotak Pertanyaan -->
                <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
                    <textarea 
                        rows="3" 
                        placeholder="Type Question Here" 
                        class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
                    >Type Question Here</textarea>
                </div>

                <!-- Grid Opsi Jawaban (Selectable) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Opsi 1 -->
                    <div class="answer-card p-8 rounded-2xl flex items-center justify-between shadow-md" onclick="toggleSelect(this)">
                        <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
                        <div class="custom-checkbox ml-4">
                            <i class="fas fa-check text-white text-lg"></i>
                        </div>
                    </div>
                    <!-- Opsi 2 (Selected Example) -->
                    <div class="answer-card selected p-8 rounded-2xl flex items-center justify-between shadow-md" onclick="toggleSelect(this)">
                        <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
                        <div class="custom-checkbox ml-4">
                            <i class="fas fa-check text-white text-lg"></i>
                        </div>
                    </div>
                    <!-- Opsi 3 -->
                    <div class="answer-card p-8 rounded-2xl flex items-center justify-between shadow-md" onclick="toggleSelect(this)">
                        <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
                        <div class="custom-checkbox ml-4">
                            <i class="fas fa-check text-white text-lg"></i>
                        </div>
                    </div>
                    <!-- Opsi 4 -->
                    <div class="answer-card p-8 rounded-2xl flex items-center justify-between shadow-md" onclick="toggleSelect(this)">
                        <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none placeholder:text-gray-500/80">
                        <div class="custom-checkbox ml-4">
                            <i class="fas fa-check text-white text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    <!-- ===== Navigasi Bawah (Updated) ===== -->
    <footer class="fixed bottom-0 left-0 right-0 bg-[#FFFBF5] p-4 border-t brand-border">
        <div class="container mx-auto flex items-center justify-between space-x-4">
            
            <!-- Tombol Settings (Kiri) -->
            <button class="bg-[#F7B5A3] text-sm text-black px-4 h-14 rounded-xl font-bold flex items-center flex-shrink-0 hover:brightness-105 transition">Settings</button>
            
            <!-- Kontainer Nomor Pertanyaan yang Bisa di-scroll -->
            <div class="flex-grow overflow-x-auto no-scrollbar">
                <div class="flex justify-start items-center space-x-2 px-2">
                    <button class="question-nav-btn px-12 py-5 active">1</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">2</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">3</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">4</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">5</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">6</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">7</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">8</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">9</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">10</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">11</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">12</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">13</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">14</button>
                    <button class="question-nav-btn bg-brand-pink-light px-12 py-5">15</button>
                </div>
            </div>
            
            <!-- Tombol Tambah (Kanan) -->
            <button class="bg-gray-800 text-white w-14 h-14 rounded-xl font-bold text-2xl hover:bg-gray-700 transition-transform hover:scale-105 flex-shrink-0 flex items-center justify-center">+</button>

        </div>
    </footer>

    </div>

    <script>
        // Function to toggle the 'selected' class on the answer card
        function toggleSelect(cardElement) {
            // This allows selecting multiple answers.
            cardElement.classList.toggle('selected');
        }
    </script>

</body>
</html>
