<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Jenius Minds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFBF5;
        }
        .brand-pink-dark-bg { background-color: #F5B9B0; }
        .brand-border { border-color: #F3EAE6; }
        
        .form-input-group {
            position: relative;
        }
        .form-input {
            background-color: #FEF6F3;
            border: 2px solid #F3EAE6;
            padding: 0.75rem 1rem;
            padding-left: 3rem; /* Ruang untuk ikon */
            width: 100%;
            border-radius: 0.75rem; /* rounded-xl */
            font-weight: 500;
            color: #374151;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #F5B9B0;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(245, 185, 176, 0.3);
        }
        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af; /* text-gray-400 */
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
    <main class="container mx-auto px-6 py-12">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-center text-4xl font-extrabold text-gray-800 mb-10">Edit Profile</h1>

            <form action="#" method="POST" class="space-y-12" x-data="{ changePassword: false }">
                
                <!-- Bagian Informasi Profil -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border brand-border">
                    <h2 class="text-2xl font-bold mb-6">Personal Information</h2>
                    
                    <!-- Foto Profil -->
                    <div class="flex items-center space-x-6 mb-8">
                        <img class="w-24 h-24 rounded-full object-cover" src="https://placehold.co/100x100/FDECE7/CC6C4F?text=M" alt="Foto Profil">
                        <div>
                            <button type="button" class="bg-brand-pink-dark-bg text-white px-5 py-2 rounded-lg font-semibold hover:brightness-105 transition">Upload new picture</button>
                            <p class="text-sm text-gray-500 mt-2">PNG, JPG, GIF up to 10MB.</p>
                        </div>
                    </div>

                    <!-- Nama Pengguna -->
                    <div class="mb-6">
                        <label for="username" class="block text-md font-semibold text-gray-600 mb-2">Username</label>
                        <div class="form-input-group">
                            <i class="fas fa-user form-icon"></i>
                            <input type="text" id="username" name="username" value="Misellin Mindany" class="form-input">
                        </div>
                    </div>

                    <!-- Email Pengguna -->
                    <div>
                        <label for="email" class="block text-md font-semibold text-gray-600 mb-2">Email</label>
                         <div class="form-input-group">
                            <i class="fas fa-envelope form-icon"></i>
                            <input type="email" id="email" name="email" value="misellinkwok@gmail.com" class="form-input" readonly>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Email address cannot be changed.</p>
                    </div>
                </div>

                <!-- Bagian Keamanan / Kata Sandi -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border brand-border">
                    <h2 class="text-2xl font-bold mb-4">Password & Security</h2>
                    <button @click.prevent="changePassword = !changePassword" type="button" class="font-semibold text-blue-600 hover:underline mb-6">
                        Change Password
                    </button>
                    
                    <div x-show="changePassword" x-transition class="space-y-6">
                        <!-- Kata Sandi Saat Ini -->
                        <div>
                            <label for="current_password" class="block text-md font-semibold text-gray-600 mb-2">Current Password</label>
                            <div class="form-input-group">
                                <i class="fas fa-lock form-icon"></i>
                                <input type="password" id="current_password" name="current_password" class="form-input" placeholder="Enter your current password">
                            </div>
                        </div>
                        <!-- Kata Sandi Baru -->
                        <div>
                            <label for="new_password" class="block text-md font-semibold text-gray-600 mb-2">New Password</label>
                            <div class="form-input-group">
                                <i class="fas fa-key form-icon"></i>
                                <input type="password" id="new_password" name="new_password" class="form-input" placeholder="Enter a new password">
                            </div>
                        </div>
                        <!-- Konfirmasi Kata Sandi Baru -->
                        <div>
                            <label for="confirm_password" class="block text-md font-semibold text-gray-600 mb-2">Confirm New Password</label>
                            <div class="form-input-group">
                                <i class="fas fa-key form-icon"></i>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Confirm your new password">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Utama -->
                <div class="flex justify-end items-center space-x-6 pt-4">
                    <a href="#" class="font-bold text-gray-600 hover:text-gray-900 transition-colors">Cancel</a>
                    <button type="submit" class="bg-brand-pink-dark-bg text-white px-8 py-3 rounded-lg font-bold shadow-md hover:brightness-105 transition-transform hover:scale-105">Save Changes</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
