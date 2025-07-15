<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz\Topic; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna yang sedang login.
     */
    public function show()
    {
        // 1. Ambil data pengguna yang sedang login
        $user = Auth::user();

        // 2. Ambil semua kuis (topik) yang dibuat oleh pengguna ini.
        //    Ini mengasumsikan ada relasi 'topics' di model User Anda.
        //    'with('subject')' digunakan untuk eager loading agar lebih efisien.
        $quizzes = Topic::where('user_id', $user->id)
                        ->with('subject')
                        ->get();

        // Definisikan data tampilan untuk setiap subject.
        // Idealnya, ini disimpan di database, tapi kita bisa definisikan di sini untuk sementara.
        $subjectColors = [
            'Mathematics' => 'bg-gray-100',
            'Computers'   => 'bg-purple-100',
            'English'     => 'bg-pink-100',
            'Chemistry'   => 'bg-blue-100',
            // Tambahkan warna untuk subject lain di sini
        ];

        // 3. Kirim data pengguna dan kuis mereka ke view
        return view('profile.myprofile', [
            'user' => $user,
            'quizzes' => $quizzes,
            'subjectColors' => $subjectColors, // Kirim data warna ke view
        ]);
    }

    // Metode 'edit' bisa ditambahkan di sini nanti
    public function edit()
    {
        // Mengambil pengguna yang sedang login dan mengirimkannya ke view
        return view('profile.editprofile', ['user' => Auth::user()]);
    }

    /**
     * Memperbarui informasi profil pengguna di database.
     */
    public function update(Request $request)
    {
        // Mengambil pengguna yang sedang login
        $user = Auth::user();

        // Validasi input dasar
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Memperbarui nama pengguna
        $user->name = $request->name;

        // Logika untuk mengubah kata sandi (jika diisi)
        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'new_password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $user->password = Hash::make($request->new_password);
        }

        // Simpan semua perubahan ke database
        $user->save();

        // Arahkan kembali ke halaman profil dengan pesan sukses
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}
