<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz\Topic; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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

        // 2. Ambil semua topik kuis milik pengguna.
        //    - with('subject') untuk mengambil data subjek terkait (Eager Loading).
        //    - withCount('questions') untuk menghitung jumlah pertanyaan di setiap kuis.
        //    Ini adalah cara yang efisien untuk mengambil semua data dalam satu query.
        $topics = Topic::where('user_id', $user->id)
                        ->with('subject')
                        ->withCount('questions')
                        ->get();

        // Definisikan data warna untuk setiap subject.
        $subjectColors = [
            'Mathematics' => 'bg-yellow-100',
            'English'     => 'bg-pink-100',
            'Chemistry'   => 'bg-blue-100',
            'Computers'   => 'bg-purple-100',
            'Biology'     => 'bg-green-100',
            'Economy'     => 'bg-gray-200',
            'Geography'   => 'bg-teal-100',
            'Physics'     => 'bg-yellow-200',
            'Music'       => 'bg-indigo-100',
            'Sports'      => 'bg-red-100',
            'Mandarin'    => 'bg-yellow-300',
        ];
        
        // 3. Kirim data yang diperlukan ke view.
        //    Kita hanya perlu mengirim 'user', 'topics', dan 'subjectColors'.
        return view('profile.myprofile', compact('user', 'topics', 'subjectColors'));
    }

    /**
     * Menampilkan halaman untuk mengedit profil.
     */
    public function edit()
    {
        return view('profile.editprofile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna di database.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Validasi email ditambahkan untuk konsistensi
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Validasi password dibuat lebih modern dan aman
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Memperbarui nama dan email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Jika ada input password baru, hash dan perbarui.
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Arahkan kembali ke halaman profil dengan pesan sukses
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}
