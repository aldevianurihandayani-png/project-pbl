@extends('layouts.admin')

@section('page_title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">

    <style>
        body, .container-fluid {
            background-color: #f4f6ff !important;
        }

        .dash-shell {
            max-width: 1120px;
            margin: 0 auto;
            padding: 10px 0 24px;
        }

        /* ===================== KOTAK ATAS ===================== */
        .dash-top-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }
        @media (max-width: 992px) {
            .dash-top-cards { grid-template-columns: 1fr; }
        }

        .dash-card-link{
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .dash-card {
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #e5edff;
            box-shadow: 0 16px 30px rgba(15,23,42,.06);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: box-shadow .15s ease, border-color .15s ease, transform .15s ease;
        }

        .dash-card-link:hover .dash-card{
            box-shadow: 0 20px 36px rgba(37,99,235,.25);
            border-color: #c7d2fe;
            transform: translateY(-2px);
        }

        .dash-card-icon {
            font-size: 26px;
            color: #1d4ed8;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dash-card-text-label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .dash-card-text-value {
            font-size: 22px;
            font-weight: 700;
            color: #0b1f4d;
            margin-top: 2px;
        }

        /* ===================== SECTION BAWAH ===================== */
        .dash-section-card {
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #e5edff;
            box-shadow: 0 16px 30px rgba(15,23,42,.06);
            margin-bottom: 16px;
        }

        .dash-section-header {
            padding: 10px 16px;
            border-bottom: 1px solid #edf2ff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dash-section-header i {
            color: #1554d1;
            font-size: 16px;
        }

        .dash-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #0b1f4d;
        }

        .dash-section-body {
            padding: 12px 18px 16px;
            font-size: 14px;
            color: #111827;
        }

        .dash-section-body ul {
            padding-left: 18px;
            margin-bottom: 0;
        }
        .dash-section-body li {
            margin-bottom: 4px;
        }

        /* link kecil di dalam teks (ke halaman menu admin) */
        .dash-link-inline{
            color:#1d4ed8;
            text-decoration:none;
        }
        .dash-link-inline:hover{
            text-decoration:underline;
        }
    </style>

    <div class="dash-shell">

        {{-- ========== KOTAK ATAS (LINK KE MENU SIDEBAR) ========== --}}
        <div class="dash-top-cards">

            {{-- Jumlah Akun → menu Akun --}}
            <a href="{{ url('admins/akun') }}" class="dash-card-link">
                <div class="dash-card">
                    <div class="dash-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="dash-card-text-label">Jumlah Akun</div>
                        <div class="dash-card-text-value">{{ $jumlahAkun }}</div>
                    </div>
                </div>
            </a>

            {{-- Mata Kuliah → menu Mata Kuliah --}}
            <a href="{{ route('admins.matakuliah.index') }}" class="dash-card-link">
                <div class="dash-card">
                    <div class="dash-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <div class="dash-card-text-label">Mata Kuliah</div>
                        <div class="dash-card-text-value">{{ $jumlahMataKuliah }}</div>
                    </div>
                </div>
            </a>

            {{-- Mahasiswa → menu Mahasiswa --}}
            <a href="{{ route('admins.mahasiswa.index') }}" class="dash-card-link">
                <div class="dash-card">
                    <div class="dash-card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <div class="dash-card-text-label">Mahasiswa</div>
                        <div class="dash-card-text-value">{{ $jumlahMahasiswa }}</div>
                    </div>
                </div>
            </a>
        </div>

        {{-- ========== STATUS DATA AKADEMIK ========== --}}
        <div class="dash-section-card">
            <div class="dash-section-header">
                <i class="fas fa-clipboard-list"></i>
                <span class="dash-section-title">Status Data Akademik</span>
            </div>

            <div class="dash-section-body">
                <p>
                    Sistem menyimpan:
                    <strong>{{ $jumlahMahasiswa }}</strong> mahasiswa,
                    <strong>{{ $jumlahKelas }}</strong> kelas,
                    <strong>{{ $jumlahMataKuliah }}</strong> mata kuliah.
                </p>

                <p class="mb-0">
                    Terakhir diperbarui:
                    <strong>
                        {{ $lastUpdatedAt ? \Carbon\Carbon::parse($lastUpdatedAt)->translatedFormat('d F Y') : '-' }}
                    </strong>.
                </p>
            </div>
        </div>

        {{-- ========== INFO AKADEMIK & KELAS (PERIODE & SEMESTER) ========== --}}
        <div class="dash-section-card">
            <div class="dash-section-header">
                <i class="fas fa-school"></i>
                <span class="dash-section-title">Info Akademik &amp; Kelas</span>
            </div>

            <div class="dash-section-body">
                @if ($periodeAktif || $semesterAktif)
                    <p>
                        Periode akademik tertinggi di data kelas:
                        <strong>{{ $periodeAktif ?? '-' }}</strong><br>
                        Semester tertinggi yang sudah diinput:
                        <strong>{{ $semesterAktif ?? '-' }}</strong>
                    </p>
                    <p class="mb-0">
                        Detail periode &amp; semester dapat diatur di menu
                        <a href="{{ route('admins.kelas.index') }}" class="dash-link-inline">
                            Akademik &gt; Kelas
                        </a>.
                    </p>
                @else
                    <p class="mb-0">
                        Belum ada data periode &amp; semester pada tabel kelas.
                        Silakan isi di menu
                        <a href="{{ route('admins.kelas.index') }}" class="dash-link-inline">
                            Akademik &gt; Kelas
                        </a>.
                    </p>
                @endif
            </div>
        </div>

        {{-- ========== STATISTIK KELAS (REAL DATA) ========== --}}
        <div class="dash-section-card">
            <div class="dash-section-header">
                <i class="fas fa-star"></i>
                <span class="dash-section-title">Statistik Kelas</span>
            </div>

            <div class="dash-section-body">
                @if ($ringkasanKelas->count() === 0)
                    <p class="mb-0">Belum ada kelas yang terdaftar.</p>
                @else
                    <ul>
                        <li>
                            Total mahasiswa:
                            <strong>
                                <a href="{{ route('admins.mahasiswa.index') }}" class="dash-link-inline">
                                    {{ $jumlahMahasiswa }}
                                </a>
                            </strong>
                        </li>

                        <li>
                            Total kelas:
                            <strong>
                                <a href="{{ route('admins.kelas.index') }}" class="dash-link-inline">
                                    {{ $jumlahKelas }}
                                </a>
                            </strong>
                        </li>

                        <li>
                            Total mata kuliah:
                            <strong>
                                <a href="{{ route('admins.matakuliah.index') }}" class="dash-link-inline">
                                    {{ $jumlahMataKuliah }}
                                </a>
                            </strong>
                        </li>

                        @if ($kelasTerbanyakMhs)
                            <li>
                                Kelas mahasiswa terbanyak:
                                <strong>{{ $kelasTerbanyakMhs->nama_kelas }}</strong>
                                ({{ $kelasTerbanyakMhs->total_mhs }} orang)
                            </li>
                        @endif

                        @if ($kelasTerbanyakMk)
                            <li>
                                Kelas mata kuliah terbanyak:
                                <strong>{{ $kelasTerbanyakMk->nama_kelas }}</strong>
                                ({{ $kelasTerbanyakMk->total_mk }} MK
                                @if($kelasTerbanyakMk->min_semester && $kelasTerbanyakMk->max_semester)
                                    , semester {{ $kelasTerbanyakMk->min_semester }} – {{ $kelasTerbanyakMk->max_semester }}
                                @endif
                                )
                            </li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>

        {{-- ========== NOTIFIKASI (REAL DARI MODEL Notification) ========== --}}
        <div class="dash-section-card">
            <div class="dash-section-header">
                <i class="fas fa-bell"></i>
                <span class="dash-section-title">Notifikasi</span>
            </div>

            <div class="dash-section-body">
                @if ($notifications && count($notifications) > 0)
                    <ul>
                        @foreach ($notifications as $notif)
                            <li>{{ $notif->message ?? $notif->judul ?? 'Notifikasi' }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="mb-0">Belum ada notifikasi baru.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
