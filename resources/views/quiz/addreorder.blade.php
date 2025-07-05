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
        }
        .brand-pink-light { background-color: #FFFBF5; }
        .brand-pink-dark-bg { background-color: #F5B9B0; }
        .brand-border { border-color: #F3EAE6; }

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
        
        /* Style for answer box with border */
        .answer-box {
            border: 2px solid #FFE2D6;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background-color: white;
            transition: all 0.2s ease;
        }
        .answer-box:hover {
            border-color: #F7B5A3;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Style for ranking selector */
        .rank-selector {
            display: flex;
            gap: 8px;
        }
        .rank-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solidrgb(228, 228, 228);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
            background-color: white;
        }
        .rank-option.selected {
            background-color: #F7B5A3;
            color: white;
            border-color: #F7B5A3;
            transform: scale(1.05);
        }
        .rank-option.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #F9F9F9;
        }

        /* Utility to hide scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
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
    <main class="container mx-auto px-8 py-8">
        
        <!-- Kotak Pertanyaan -->
        <div class="bg-[#F7B5A3] p-12 rounded-xl shadow-lg mb-10">
            <textarea 
                rows="3" 
                placeholder="Type Question Here" 
                class="w-full bg-transparent text-2xl font-bold placeholder:text-gray-500/80 focus:outline-none resize-none"
            >Type Question Here</textarea>
        </div>

        <!-- Daftar Jawaban untuk Diurutkan -->
        <div class="space-y-4" id="answer-container">
            <!-- Opsi 1 -->
            <div class="answer-box answer-item bg-[#FFE2D6]" data-id="1">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 w-full">
                        <span class="text-lg font-semibold">1.</span>
                        <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none border-b-2 border-transparent focus:border-gray-300 transition-colors">
                    </div>
                    <div class="rank-selector">
                        <div class="rank-option" data-rank="1" onclick="selectRank(this, 1)">1</div>
                        <div class="rank-option" data-rank="2" onclick="selectRank(this, 2)">2</div>
                        <div class="rank-option" data-rank="3" onclick="selectRank(this, 3)">3</div>
                        <div class="rank-option" data-rank="4" onclick="selectRank(this, 4)">4</div>
                    </div>
                </div>
            </div>
            
            <!-- Opsi 2 -->
            <div class="answer-box answer-item bg-[#FFE2D6]" data-id="2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 w-full">
                        <span class="text-lg font-semibold">2.</span>
                        <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none border-b-2 border-transparent focus:border-gray-300 transition-colors">
                    </div>
                    <div class="rank-selector">
                        <div class="rank-option" data-rank="1" onclick="selectRank(this, 1)">1</div>
                        <div class="rank-option" data-rank="2" onclick="selectRank(this, 2)">2</div>
                        <div class="rank-option" data-rank="3" onclick="selectRank(this, 3)">3</div>
                        <div class="rank-option" data-rank="4" onclick="selectRank(this, 4)">4</div>
                    </div>
                </div>
            </div>
            
            <!-- Opsi 3 -->
            <div class="answer-box answer-item bg-[#FFE2D6]" data-id="3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 w-full">
                        <span class="text-lg font-semibold">3.</span>
                        <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none border-b-2 border-transparent focus:border-gray-300 transition-colors">
                    </div>
                    <div class="rank-selector">
                        <div class="rank-option" data-rank="1" onclick="selectRank(this, 1)">1</div>
                        <div class="rank-option" data-rank="2" onclick="selectRank(this, 2)">2</div>
                        <div class="rank-option" data-rank="3" onclick="selectRank(this, 3)">3</div>
                        <div class="rank-option" data-rank="4" onclick="selectRank(this, 4)">4</div>
                    </div>
                </div>
            </div>
            
            <!-- Opsi 4 -->
            <div class="answer-box answer-item bg-[#FFE2D6]" data-id="4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 w-full">
                        <span class="text-lg font-semibold">4.</span>
                        <input type="text" value="Type Answer Here" class="w-full bg-transparent text-lg font-semibold focus:outline-none border-b-2 border-transparent focus:border-gray-300 transition-colors">
                    </div>
                    <div class="rank-selector">
                        <div class="rank-option" data-rank="1" onclick="selectRank(this, 1)">1</div>
                        <div class="rank-option" data-rank="2" onclick="selectRank(this, 2)">2</div>
                        <div class="rank-option" data-rank="3" onclick="selectRank(this, 3)">3</div>
                        <div class="rank-option" data-rank="4" onclick="selectRank(this, 4)">4</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="mt-8 flex justify-center">
            <button onclick="validateRanking()" class="bg-[#F7B5A3] text-black px-8 py-3 rounded-xl font-bold text-lg hover:brightness-105 transition hover:shadow-md">
                Submit Jawaban
            </button>
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

    <script>
        let selectedRanks = {};
        
        function selectRank(element, rank) {
            const answerItem = element.closest('.answer-item');
            const answerId = answerItem.getAttribute('data-id');
            
            // Remove selected class from all options in this answer
            const allOptions = answerItem.querySelectorAll('.rank-option');
            allOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            element.classList.add('selected');
            
            // Store the selected rank
            selectedRanks[answerId] = rank;
            
            // Update UI to show which ranks are already taken
            updateRankAvailability();
        }
        
        function updateRankAvailability() {
            const allRankOptions = document.querySelectorAll('.rank-option');
            const usedRanks = Object.values(selectedRanks);
            
            allRankOptions.forEach(option => {
                const rank = parseInt(option.getAttribute('data-rank'));
                const isUsed = usedRanks.includes(rank);
                
                // Find if this rank is selected by another answer
                const answerItem = option.closest('.answer-item');
                const answerId = answerItem.getAttribute('data-id');
                const isCurrentSelection = selectedRanks[answerId] === rank;
                
                if (isUsed && !isCurrentSelection) {
                    option.classList.add('disabled');
                } else {
                    option.classList.remove('disabled');
                }
            });
        }
        
        function validateRanking() {
            const allAnswers = document.querySelectorAll('.answer-item');
            const selectedCount = Object.keys(selectedRanks).length;
            
            if (selectedCount < allAnswers.length) {
                alert('Silakan beri peringkat untuk semua opsi jawaban!');
                return;
            }
            
            // Check for duplicate ranks
            const rankValues = Object.values(selectedRanks);
            const uniqueRanks = [...new Set(rankValues)];
            
            if (uniqueRanks.length !== rankValues.length) {
                alert('Tidak boleh ada peringkat yang sama!');
                return;
            }
            
            // If validation passes
            alert('Jawaban berhasil disimpan!\n\nHasil ranking:\n' + 
                  Object.entries(selectedRanks).map(([id, rank]) => 
                    `${rank}. ${document.querySelector(`.answer-item[data-id="${id}"] input`).value}`
                  ).join('\n'));
        }
    </script>

</body>
</html>