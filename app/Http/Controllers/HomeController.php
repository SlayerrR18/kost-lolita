<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kost; // Pastikan model Kost di-import

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (single-page) yang berisi semua informasi.
     */
    public function index()
    {
        // Ambil data untuk bagian "Kamar Tersedia"
        // Hanya kamar dengan status "Kosong", ambil 6 terbaru
        $kosts_tersedia = Kost::where('status', 'Kosong')->latest()->take(6)->get();

        // Ambil semua data kost untuk bagian "Hubungi Kami" / Daftar semua kamar
        $kosts_semua = Kost::all();

        // Kirim semua data yang dibutuhkan ke satu view 'home'
        return view('home', compact('kosts_tersedia', 'kosts_semua'));
    }
}

