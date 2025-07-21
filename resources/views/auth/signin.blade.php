<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Jenius Minds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFAF3;
        }
        .brand-pink-dark { color: #C96944; }
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
        /* Style baru untuk pesan error di bawah input */
        .error-message {
            color: #c53030; /* Warna merah untuk error */
            font-size: 0.875rem; /* Ukuran teks kecil */
            margin-top: 0.5rem; /* Jarak dari atas */
        }
    </style>
</head>
<body class="text-gray-700">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8">
                <!-- Logo Section -->
                <div class="flex justify-center mb-4"> 
                    <div class="flex items-center space-x-3 group">
                        <img src="{{ asset('assets/Logo.png') }}" alt="Logo Jenius Minds" class="rounded-full w-12 h-12">
                        <span class="text-3xl font-bold text-gray-800">Jenius Minds</span>
                    </div>
                </div>

                <!-- Header Section -->
                <div class="mb-8">
                    <h2 class="text-2xl text-center font-bold text-gray-800 mb-2">Welcome Back!</h2>
                    <p class="text-gray-500 text-center">Sign in to continue your journey of fun learning.</p>
                </div>

                <!-- Form Section -->
                <form action="{{ route('authenticate') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-600">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </span>
                            <input type="email" id="email" name="email" placeholder="you@example.com" class="w-full pl-10 pr-4 py-3 rounded-lg form-input focus:outline-none" value="{{ old('email') }}" required>
                        </div>
                        {{-- Pesan error untuk email --}}
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="text-sm font-medium text-gray-600">Password</label>
                            <a href="#" class="text-sm font-bold brand-pink-dark hover:underline">Forget Password?</a>
                        </div>
                        <div class="relative">
                           <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-lock text-gray-400"></i>
                            </span>
                            <input type="password" id="password" name="password" placeholder="Enter your password" class="w-full pl-10 pr-4 py-3 rounded-lg form-input focus:outline-none" required>
                        </div>
                        {{-- Pesan error untuk password --}}
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full btn-gradient text-black font-bold py-3 px-4 rounded-lg shadow-md text-lg">
                            Sign In
                        </button>
                    </div>
                </form>

                <!-- Footer Link -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        Don't have account yet? <a href={{ route('signup') }} class="font-bold brand-pink-dark hover:underline">Create One</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
