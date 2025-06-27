<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Jenius Minds</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Icon Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFBF5;
        }
        .brand-pink-dark { color: #EEA99D; }
        .btn-gradient {
            background-image: linear-gradient(to right, #F5B9B0, #EEA99D);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(238, 169, 157, 0.7);
        }
        .form-input {
            background-color: #FEF6F3;
            border: 1px solid #F3EAE6;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            background-color: #FFFFFF;
            border-color: #EEA99D;
            box-shadow: 0 0 0 2px rgba(238, 169, 157, 0.3);
        }
    </style>
</head>
<body class="text-gray-700">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="container mx-auto max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
            
            <!-- Kolom Kiri: Form Sign In -->
            <div class="p-8 md:p-12">
                <!-- Logo -->
                 <a href="/" class="flex items-center space-x-3 group mb-8">
                    <img src="https://placehold.co/40x40/EEA99D/FFFFFF?text=JM" alt="Logo Jenius Minds" class="rounded-full">
                    <span class="text-xl font-bold text-gray-800">Jenius Minds</span>
                </a>

                <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Welcome Back!</h1>
                <p class="text-gray-500 mb-8">Sign in to continue your journey of fun learning.</p>

                <form action="{{ route('authenticate') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-600">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </span>
                             <input type="email" id="email" name="email" placeholder="you@example.com" class="w-full pl-10 pr-4 py-3 rounded-lg form-input focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="text-sm font-medium text-gray-600">Password</label>
                            <a href="#" class="text-sm font-medium text-pink-500 hover:underline">Forgot Password?</a>
                        </div>
                        <div class="relative">
                           <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-lock text-gray-400"></i>
                            </span>
                            <input type="password" id="password" name="password" placeholder="Enter your password" class="w-full pl-10 pr-4 py-3 rounded-lg form-input focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full btn-gradient text-white font-bold py-3 px-4 rounded-lg shadow-md text-lg">
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        Don't have an account? <a href={{ route('signup') }} class="font-bold brand-pink-dark hover:underline">Sign Up</a>
                    </p>
                </div>
            </div>

            <!-- Kolom Kanan: Gambar/Ilustrasi -->
            <div class="hidden md:block bg-pink-100 p-12 flex flex-col items-center justify-center text-center rounded-r-2xl" style="background-color: #FEF6F3;">
                <img src="https://placehold.co/300x300/FFFFFF/EEA99D?text=Learn" alt="Ilustrasi Belajar" class="rounded-2xl mb-8 shadow-xl">
                 <h2 class="text-2xl font-bold text-gray-800">Unlock a World of Knowledge</h2>
                 <p class="text-gray-600 mt-2 max-w-sm mx-auto">Join thousands of learners and start your quiz adventure today!</p>
            </div>

        </div>
    </div>

</body>
</html>
