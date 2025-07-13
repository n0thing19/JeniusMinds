<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     */
    public function show(): View
    {
        // Di sini Anda bisa mengambil data pengguna yang sedang login
        // $user = auth()->user();
        // return view('profile.myprofile', ['user' => $user]);

        return view('profile.myprofile');
    }

    /**
     * Menampilkan halaman untuk mengedit profil pengguna.
     */
    public function edit(): View
    {
        // Di sini juga bisa mengambil data user untuk ditampilkan di form
        // $user = auth()->user();
        // return view('profile.editprofile', ['user' => $user]);

        return view('profile.editprofile');
    }

    /**
     * (Opsional) Method untuk memproses update data profil dari form.
     * Anda akan membutuhkan ini nanti.
     */
    // public function update(Request $request)
    // {
    //     // Logika untuk validasi dan update data pengguna
    //     // ...
    //
    //     return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    // }
}