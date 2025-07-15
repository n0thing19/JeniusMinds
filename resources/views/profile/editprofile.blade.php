@extends('layouts.app') {{-- Asumsi Anda menggunakan layout utama 'app' --}}

@section('title', 'Edit Profile')

@push('styles')
<style>
    .form-input-group { position: relative; }
    .form-input {
        background-color: #FEF6F3;
        border: 2px solid #F3EAE6;
        padding: 0.75rem 1rem;
        padding-left: 3rem; /* Ruang untuk ikon */
        width: 100%;
        border-radius: 0.75rem;
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
        color: #9ca3af;
    }
</style>
@endpush

@section('content')
<main class="container mx-auto px-6 py-12">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-center text-4xl font-extrabold text-gray-800 mb-10">Edit Profile</h1>

        {{-- Menampilkan pesan error validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6" role="alert">
                <strong class="font-bold">Oops!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-12" x-data="{ changePassword: false }">
            @csrf
            @method('PATCH') {{-- Metode HTTP untuk update --}}
            
            <!-- Bagian Informasi Profil -->
            <div class="bg-white p-8 rounded-2xl shadow-lg border brand-border">
                <h2 class="text-2xl font-bold mb-6">Personal Information</h2>
                
                <div class="flex items-center space-x-6 mb-8">
                    <img class="w-24 h-24 rounded-full object-cover" src="https://placehold.co/100x100/FDECE7/CC6C4F?text={{ substr($user->name, 0, 1) }}" alt="Foto Profil">
                    <div>
                        <button type="button" class="bg-[#F7B5A3] text-black px-5 py-2 rounded-lg font-semibold hover:brightness-105 transition">Upload picture</button>
                        <p class="text-sm text-gray-500 mt-2">PNG, JPG, GIF up to 10MB.</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="name" class="block text-md font-semibold text-gray-600 mb-2">Username</label>
                    <div class="form-input-group">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-input">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-md font-semibold text-gray-600 mb-2">Email</label>
                     <div class="form-input-group">
                        <i class="fas fa-envelope form-icon"></i>
                        <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-input bg-gray-100 cursor-not-allowed" readonly>
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
                    <div>
                        <label for="current_password" class="block text-md font-semibold text-gray-600 mb-2">Current Password</label>
                        <div class="form-input-group">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" id="current_password" name="current_password" class="form-input" placeholder="Enter your current password">
                        </div>
                    </div>
                    <div>
                        <label for="new_password" class="block text-md font-semibold text-gray-600 mb-2">New Password</label>
                        <div class="form-input-group">
                            <i class="fas fa-key form-icon"></i>
                            <input type="password" id="new_password" name="new_password" class="form-input" placeholder="Enter a new password">
                        </div>
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="block text-md font-semibold text-gray-600 mb-2">Confirm New Password</label>
                        <div class="form-input-group">
                            <i class="fas fa-key form-icon"></i>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input" placeholder="Confirm your new password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end items-center space-x-6 pt-4">
                <a href="{{ route('profile.show') }}" class="font-bold text-gray-600 hover:text-gray-900 transition-colors">Cancel</a>
                <button type="submit" class="bg-[#F7B5A3] text-black px-8 py-3 rounded-lg font-bold shadow-md hover:brightness-105 transition-transform hover:scale-105">Save Changes</button>
            </div>
        </form>
    </div>
</main>
@endsection
