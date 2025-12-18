{{-- resources/views/dosenpenguji/bantuan.blade.php --}}
@extends('dosenpenguji.layout')

@section('title', 'Bantuan')
@section('header', 'Bantuan')

@section('content')
<style>
  /* ===== Help Center (WhatsApp-ish + Accordion) ===== */
  .help-wrap{display:grid;gap:18px}
  .help-hero{
    padding:16px 18px;border-radius:16px;
    background:linear-gradient(180deg, rgba(14,37,122,.08), rgba(14,37,122,.02));
    border:1px solid rgba(13,23,84,.10);
  }
  .help-hero h2{margin:0 0 6px;color:#0e257a}
  .help-hero p{margin:0;color:#6c7a8a;line-height:1.5}

  .help-search{display:flex;gap:10px;flex-wrap:wrap}
  .help-search input{
    flex:1;min-width:240px;
    padding:10px 12px;border:1px solid #d8dfeb;border-radius:10px;
    outline:none;
  }
  .pill{
    display:inline-flex;gap:8px;align-items:center;
    padding:8px 12px;border-radius:999px;
    border:1px solid rgba(13,23,84,.12);
    background:#fff;color:#0e257a;font-weight:700;font-size:13px;
    cursor:pointer;
  }
  .pill.active{background:#e9efff}

  .help-grid{display:grid;grid-template-columns:1fr;gap:16px}
  @media (min-width: 960px){
    .help-grid{grid-template-columns:1.2fr .8fr}
  }

  /* Accordion */
  .acc{display:grid;gap:10px}
  .acc-item{
    border:1px solid rgba(13,23,84,.10);
    border-radius:14px;overflow:hidden;background:#fff;
  }
  .acc-btn{
    width:100%;text-align:left;border:0;background:#fff;cursor:pointer;
    padding:12px 14px;display:flex;align-items:center;justify-content:space-between;
    font-weight:800;color:#0e257a;
  }
  .acc-btn small{font-weight:700;color:#6c7a8a}
  .acc-panel{display:none;padding:0 14px 14px;color:#233042}
  .acc-panel ul{margin:10px 0 0;padding-left:18px}
  .acc-panel li{margin:6px 0;color:#233042}
  .acc-item.open .acc-panel{display:block}
  .acc-item.open .chev{transform:rotate(180deg)}
  .chev{transition:transform .15s;opacity:.85}

  /* Side card */
  .side-list{display:grid;gap:10px}
  .side-list .row{display:flex;gap:10px;align-items:flex-start}
  .badge{
    width:28px;height:28px;border-radius:10px;display:grid;place-items:center;
    background:#e9efff;color:#1d4ed8;font-weight:800;
  }
  .muted{color:#6c7a8a}

  /* WhatsApp-ish bubbles for highlight */
  .chat{background:#e5ddd5;border-radius:16px;padding:14px;border:1px solid rgba(13,23,84,.10)}
  .bubble{max-width:92%;padding:10px 12px;border-radius:14px;margin:8px 0;line-height:1.5}
  .bubble.admin{background:#fff}
  .bubble.user{background:#dcf8c6;margin-left:auto}
  .time{font-size:11px;color:#6c7a8a;text-align:right;margin-top:6px}
</style>

<div class="help-wrap">

  {{-- HERO --}}
  <div class="help-hero">
    <h2>Halaman Bantuan (Help Center)</h2>
    <p>
      Dokumen ini menjelaskan <b>konsep, struktur, dan isi</b> halaman <b>Bantuan</b> untuk aplikasi/website Anda.
      Bisa dipakai sebagai <b>acuan desain</b>, <b>konten awal</b>, atau <b>brief</b> untuk developer & UI/UX.
    </p>
  </div>

  {{-- SEARCH + FILTER --}}
  <div class="help-search">
    <input id="helpSearch" type="text" placeholder="Cari bantuan… (contoh: edit profil, login, logout)" />

    <button class="pill active" type="button" data-filter="all">
      <i class="fa-solid fa-layer-group"></i> Semua
    </button>
    <button class="pill" type="button" data-filter="akun">
      <i class="fa-solid fa-id-badge"></i> Akun & Profil
    </button>
    <button class="pill" type="button" data-filter="pakai">
      <i class="fa-solid fa-gears"></i> Penggunaan
    </button>
    <button class="pill" type="button" data-filter="teknis">
      <i class="fa-solid fa-screwdriver-wrench"></i> Teknis
    </button>
    <button class="pill" type="button" data-filter="privasi">
      <i class="fa-solid fa-shield-halved"></i> Privasi
    </button>
    <button class="pill" type="button" data-filter="faq">
      <i class="fa-solid fa-circle-question"></i> FAQ
    </button>
  </div>

  <div class="help-grid">

    {{-- KIRI: ACCORDION --}}
    <div class="card">
      <div class="card-hd">
        <span>Pusat Bantuan</span>
        <small class="muted">Klik pertanyaan untuk melihat jawaban</small>
      </div>
      <div class="card-bd">

        <div class="acc" id="helpAcc">

          {{-- 1. Tujuan --}}
          <div class="acc-item" data-cat="all">
            <button class="acc-btn" type="button">
              <span>1. Tujuan Halaman Bantuan</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <ul>
                <li>Membantu pengguna memahami fitur aplikasi</li>
                <li>Menjawab masalah umum tanpa perlu kontak admin</li>
                <li>Mengurangi pertanyaan berulang ke tim support</li>
                <li>Memberi panduan langkah demi langkah</li>
              </ul>
            </div>
          </div>

          {{-- 2. Struktur --}}
          <div class="acc-item" data-cat="all">
            <button class="acc-btn" type="button">
              <span>2. Struktur Halaman Bantuan (Disarankan)</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <ul>
                <li><b>Header</b>: Judul “Pusat Bantuan/Bantuan” + subjudul</li>
                <li><b>Pencarian Bantuan</b> (opsional): input “Cari bantuan…”</li>
                <li><b>Kategori</b>: Akun & Profil, Penggunaan, Privasi, Teknis, FAQ</li>
              </ul>
            </div>
          </div>

          {{-- 3.1 Akun & Profil --}}
          <div class="acc-item" data-cat="akun">
            <button class="acc-btn" type="button">
              <span>Bagaimana cara mengedit profil?</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <ul>
                <li>Klik foto profil di pojok kanan atas</li>
                <li>Pilih menu <b>Edit Profil</b></li>
                <li>Ubah data yang diperlukan</li>
                <li>Klik <b>Simpan</b></li>
              </ul>
            </div>
          </div>

          <div class="acc-item" data-cat="akun">
            <button class="acc-btn" type="button">
              <span>Bagaimana cara keluar (logout)?</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <ul>
                <li>Klik foto profil</li>
                <li>Pilih menu <b>Keluar</b></li>
                <li>Sistem akan mengakhiri sesi Anda</li>
              </ul>
            </div>
          </div>

          {{-- 3.2 Penggunaan Aplikasi --}}
          <div class="acc-item" data-cat="pakai">
            <button class="acc-btn" type="button">
              <span>Apa fungsi menu Lihat Profil?</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <p class="muted" style="margin:10px 0 0">
                Menu ini digunakan untuk melihat informasi akun pengguna seperti nama, email, dan peran.
              </p>
            </div>
          </div>

          <div class="acc-item" data-cat="pakai">
            <button class="acc-btn" type="button">
              <span>Bagaimana cara menggunakan fitur utama aplikasi?</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <p class="muted" style="margin:10px 0 0">
                Jelaskan sesuai fitur aplikasi Anda (misalnya input data, laporan, dashboard, dll).
              </p>
            </div>
          </div>

          {{-- 3.3 Masalah Teknis --}}
          <div class="acc-item" data-cat="teknis">
            <button class="acc-btn" type="button">
              <span>Tidak bisa login</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <ul>
                <li>Pastikan email dan password benar</li>
                <li>Cek koneksi internet</li>
                <li>Gunakan fitur <i>Lupa Password</i></li>
              </ul>
            </div>
          </div>

          <div class="acc-item" data-cat="teknis">
            <button class="acc-btn" type="button">
              <span>Aplikasi error atau lambat</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <ul>
                <li>Refresh halaman</li>
                <li>Logout lalu login kembali</li>
                <li>Hubungi admin jika masalah berlanjut</li>
              </ul>
            </div>
          </div>

          {{-- 3.4 Keamanan & Privasi --}}
          <div class="acc-item" data-cat="privasi">
            <button class="acc-btn" type="button">
              <span>Apakah data saya aman?</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <p class="muted" style="margin:10px 0 0">
                Ya, data pengguna disimpan dengan sistem keamanan dan hanya digunakan sesuai kebijakan privasi.
              </p>
            </div>
          </div>

          <div class="acc-item" data-cat="privasi">
            <button class="acc-btn" type="button">
              <span>Apakah boleh membagikan akun?</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <p class="muted" style="margin:10px 0 0">
                Tidak disarankan demi keamanan data.
              </p>
            </div>
          </div>

          {{-- 3.5 FAQ --}}
          <div class="acc-item" data-cat="faq">
            <button class="acc-btn" type="button">
              <span>Q: Apakah aplikasi ini gratis?</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <p class="muted" style="margin:10px 0 0">
                A: (Sesuaikan dengan kebijakan aplikasi)
              </p>
            </div>
          </div>

          <div class="acc-item" data-cat="faq">
            <button class="acc-btn" type="button">
              <span>Q: Siapa yang bisa saya hubungi jika ada masalah?</span>
              <i class="fa-solid fa-chevron-down chev"></i>
            </button>
            <div class="acc-panel">
              <p class="muted" style="margin:10px 0 0">
                A: Silakan hubungi admin melalui email/WA yang tersedia.
              </p>
            </div>
          </div>

        </div>

        <div class="muted" style="margin-top:12px;font-size:12px">
          Tips: gunakan kotak pencarian untuk memfilter pertanyaan.
        </div>

      </div>
    </div>

    {{-- KANAN: INFO KONTAK + FILE --}}
    <div style="display:grid;gap:16px">

      <div class="card">
        <div class="card-hd">Kontak Bantuan</div>
        <div class="card-bd side-list">
          <div class="row">
            <div class="badge"><i class="fa-solid fa-envelope"></i></div>
            <div>
              <div style="font-weight:800;color:#0e257a">Email Support</div>
              <div class="muted">support@simap-politala.ac.id'</div>
            </div>
          </div>

          <div class="row">
            <div class="badge"><i class="fa-brands fa-whatsapp"></i></div>
            <div>
              <div style="font-weight:800;color:#0e257a">WhatsApp Admin</div>
              <div class="muted">+62 822-5472-4885</div>
            </div>
          </div>

          <div class="row">
            <div class="badge"><i class="fa-solid fa-clock"></i></div>
            <div>
              <div style="font-weight:800;color:#0e257a">Jam Operasional</div>
              <div class="muted">Senin–Jumat, 08.00–16.00</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-hd">File yang Perlu Diolah / Dibuat</div>
        <div class="card-bd">
          <div style="font-weight:800;color:#0e257a;margin-bottom:8px">Wajib</div>
          <ul style="margin:0 0 12px;padding-left:18px">
            <li><b>help.md / help.html</b> → konten bantuan</li>
            <li><b>help.json</b> → jika bantuan mau dinamis</li>
          </ul>

          <div style="font-weight:800;color:#0e257a;margin-bottom:8px">Opsional</div>
          <ul style="margin:0;padding-left:18px">
            <li><b>FAQ.md</b></li>
            <li><b>privacy.md</b></li>
            <li><b>terms.md</b></li>
          </ul>
        </div>
      </div>

      {{-- preview chat ala WA --}}
      <div class="card">
        <div class="card-hd">Contoh Tampilan (Chat)</div>
        <div class="card-bd">
          <div class="chat">
            <div class="bubble admin">
              <b>Admin</b><br>
              Halo 👋 Ini Pusat Bantuan. Pilih pertanyaan atau cari di atas ya.
              <div class="time">{{ now()->format('H:i') }}</div>
            </div>
            <div class="bubble user">
              Bagaimana cara mengedit profil?
              <div class="time">{{ now()->format('H:i') }}</div>
            </div>
            <div class="bubble admin">
              <b>Admin</b><br>
              1) Klik foto profil<br>
              2) Pilih <b>Edit Profil</b><br>
              3) Ubah data<br>
              4) Klik <b>Simpan</b>
              <div class="time">{{ now()->format('H:i') }}</div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>

<script>
  // Accordion toggle
  document.addEventListener('DOMContentLoaded', function(){
    const items = document.querySelectorAll('#helpAcc .acc-item');
    items.forEach(item => {
      const btn = item.querySelector('.acc-btn');
      btn.addEventListener('click', () => item.classList.toggle('open'));
    });

    // Filter pills + search
    const pills = document.querySelectorAll('.pill[data-filter]');
    const search = document.getElementById('helpSearch');
    let activeFilter = 'all';

    function applyFilter(){
      const q = (search.value || '').toLowerCase().trim();
      items.forEach(item => {
        const cat = item.getAttribute('data-cat') || 'all';
        const text = item.innerText.toLowerCase();
        const okCat = (activeFilter === 'all') || (cat === activeFilter);
        const okText = (q === '') || text.includes(q);

        item.style.display = (okCat && okText) ? '' : 'none';
      });
    }

    pills.forEach(p => {
      p.addEventListener('click', () => {
        pills.forEach(x => x.classList.remove('active'));
        p.classList.add('active');
        activeFilter = p.getAttribute('data-filter');
        applyFilter();
      });
    });

    search.addEventListener('input', applyFilter);
  });
</script>
@endsection
