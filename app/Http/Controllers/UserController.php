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
            'role'     => ['required', Rule::in([
                'dosen_pembimbing','admins','mahasiswa','jaminan_mutu','koor_pbl','dosen_penguji',
            ])],
            'password' => ['required','min:6','confirmed'],
        ]);

        $user = self::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        try {
            Mail::to($user->email)->queue(new RegisterSuccessMail($user->name));
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email register: '.$e->getMessage());
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Redirect sesuai role
        if ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Registrasi berhasil. Email konfirmasi telah dikirim.');
        }

        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil. Email konfirmasi telah dikirim.');
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

        // Validasi
        $data = $request->validate([
            'name'                  => ['required','string','max:100'],
            'email'                 => ['required','email','max:150','unique:users,email'],
            'password'              => ['required','min:6','confirmed'],
            'role'                  => ['required','in:admins,dosen_pembimbing,dosen_penguji,koordinator,jaminan_mutu,mahasiswa'],
            // nim/prodi opsional (belum disimpan ke tabel users)
            'nim'                   => ['nullable','string','max:50'],
            'prodi'                 => ['nullable','string','max:100'],
        ]);

        // Simpan user (hanya kolom yang memang ada di tabel users)
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        // Auto login
        Auth::login($user);

        // Redirect per-role
        $route = match ($user->role) {
            'admin'            => 'admins.dashboard',
            'dosen_pembimbing'  => 'dosen.dashboard',
            'dosen_penguji'     => 'dosenpenguji.dashboard',
            'koordinator'       => 'koordinator.dashboard',
            'jaminan_mutu'      => 'jaminanmutu.dashboard',
            'mahasiswa'         =>  'mahasiswa.dashboard',
            default             => 'mhs.dashboard',
        };

        return redirect()->route($route)->with('success','Registrasi berhasil. Selamat datang!');
    }
}
