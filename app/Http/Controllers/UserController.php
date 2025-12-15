<?php


namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterSuccessMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserController extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'foto',
        'email_verified_at',

        // ✅ supaya pending kebaca
        'status',
        'requested_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],

            // ✅ role dirapikan (admins/koor_pbl tetap diterima tapi dinormalisasi)
            'role'     => ['required', Rule::in([
                'dosen_pembimbing','admins','admin','mahasiswa','jaminan_mutu','koor_pbl','koordinator','dosen_penguji',
            ])],

            'password' => ['required','min:6','confirmed'],
        ]);

        // ✅ NORMALISASI ROLE agar konsisten ke sistem (admin/koordinator)
        $requestedRole = $data['role'];
        if ($requestedRole === 'admins') $requestedRole = 'admin';
        if ($requestedRole === 'koor_pbl') $requestedRole = 'koordinator';

        $user = self::create([
            'name'     => $data['name'],
            'email'    => $data['email'],

            // ✅ role final jangan langsung ikut "diminta" → biar admin yang set
            // aman: default mahasiswa
            'role'     => 'mahasiswa',

            // ✅ simpan role yang diminta user
            'requested_role' => $requestedRole,

            // ✅ pending dulu
            'status'   => 'pending',

            'password' => Hash::make($data['password']),
        ]);

        try {
            Mail::to($user->email)->queue(new RegisterSuccessMail($user->name));
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email register: '.$e->getMessage());
        }

        // ✅ JANGAN AUTO LOGIN karena masih pending (harus disetujui admin)
        // Auth::login($user);
        // $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil. Email konfirmasi telah dikirim. Akun menunggu persetujuan admin.');
    }
}


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // TERIMA "name" ATAU fallback dari "nama" (jaga-jaga form lama)
        $request->merge([
            'name' => $request->input('name') ?? $request->input('nama'),
        ]);

        // ✅ Normalisasi role input (admins/koor_pbl -> admin/koordinator)
        $roleInput = $request->input('role');
        if ($roleInput === 'admins') $roleInput = 'admin';
        if ($roleInput === 'koor_pbl') $roleInput = 'koordinator';
        $request->merge(['role' => $roleInput]);

        // Validasi
        $data = $request->validate([
            'name'                  => ['required','string','max:100'],
            'email'                 => ['required','email','max:150','unique:users,email'],
            'password'              => ['required','min:6','confirmed'],

            // ✅ izinkan variasi yang kamu pakai tapi diarahkan jadi baku
            'role'                  => ['required','in:admin,dosen_pembimbing,dosen_penguji,koordinator,jaminan_mutu,mahasiswa'],

            // nim/prodi opsional (belum disimpan ke tabel users)
            'nim'                   => ['nullable','string','max:50'],
            'prodi'                 => ['nullable','string','max:100'],
        ]);

        // ✅ REGISTER USER BIASA = PENDING, role final ditentukan admin
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),

            // role final default dulu
            'role'     => 'mahasiswa',

            // simpan role yang diminta
            'requested_role' => $data['role'],

            // pending
            'status' => 'pending',
        ]);

        // ✅ JANGAN AUTO LOGIN (pending harus disetujui admin)
        // Auth::login($user);

        return redirect()
            ->route('home')
            ->with('success', 'Registrasi berhasil. Akun menunggu persetujuan admin.');
    }
}
