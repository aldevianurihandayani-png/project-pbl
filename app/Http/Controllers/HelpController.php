<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    private array $faqs = [
        [
            'slug' => 'edit-profil',
            'q' => 'Bagaimana cara mengedit profil?',
            'a' => [
                'Klik foto profil di pojok kanan atas.',
                'Pilih menu "Edit Profil".',
                'Ubah data yang diperlukan.',
                'Klik "Simpan".',
            ],
        ],
        [
            'slug' => 'lihat-profil',
            'q' => 'Bagaimana cara melihat profil?',
            'a' => [
                'Klik foto profil di pojok kanan atas.',
                'Pilih menu "Lihat Profil".',
            ],
        ],
        [
            'slug' => 'logout',
            'q' => 'Bagaimana cara keluar (logout)?',
            'a' => [
                'Klik foto profil di pojok kanan atas.',
                'Pilih menu "Keluar".',
            ],
        ],
        [
            'slug' => 'tidak-bisa-login',
            'q' => 'Tidak bisa login, bagaimana solusinya?',
            'a' => [
                'Pastikan email & password benar.',
                'Cek koneksi internet.',
                'Gunakan fitur "Lupa Password" jika ada.',
                'Jika masih gagal, hubungi admin.',
            ],
        ],
    ];

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $items = $this->faqs;
        if ($search !== '') {
            $items = array_values(array_filter($items, function ($it) use ($search) {
                return str_contains(mb_strtolower($it['q']), mb_strtolower($search));
            }));
        }

        return view('help.index', [
            'items' => $items,
            'search' => $search,
        ]);
    }

    // optional: halaman detail per pertanyaan
    public function show(string $slug)
    {
        $item = collect($this->faqs)->firstWhere('slug', $slug);
        abort_if(!$item, 404);

        return view('help.show', ['item' => $item]);
    }
}
