{{-- resources/views/help/index.blade.php --}}
@extends('dosenpenguji.layout')

@section('title', 'Bantuan')
@section('header', 'Bantuan')

@section('content')
@php
  $search = $search ?? '';
  $items  = $items ?? [];
@endphp

<style>
  .help-grid{display:grid;grid-template-columns:1fr;gap:16px}
  @media (min-width: 980px){ .help-grid{grid-template-columns:1.25fr .75fr;} }

  .faq-item{
    border:1px solid rgba(13,23,84,.12);
    border-radius:12px;
    margin-bottom:12px;
    overflow:hidden;
    background:#fff;
  }
  .faq-q{
    padding:14px 16px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-weight:800;
    color:#0e257a;
  }
  .faq-q:hover{ background:#f4f7ff }
  .faq-a{
    display:none;
    padding:14px 18px;
    border-top:1px solid #eef1f6;
    color:#233042;
    line-height:1.6;
  }
  .faq-item.open .faq-a{ display:block }
  .faq-item.open .chev{ transform:rotate(180deg) }
  .chev{ transition:transform .15s; opacity:.85 }

  .mini{font-size:12px;color:#6c7a8a;font-weight:600}
  .chip{
    display:inline-flex;align-items:center;gap:8px;
    padding:8px 10px;border-radius:999px;
    border:1px solid rgba(13,23,84,.12);
    background:#fff;color:#0e257a;font-weight:800;font-size:12px;
  }
  .list-clean{margin:8px 0 0;padding-left:18px}
  .list-clean li{margin:6px 0}
</style>

<div class="card">
  <div class="card-hd">
    <div>
      <div style="font-weight:900">Pusat Bantuan</div>
      <div class="mini">Panduan penggunaan aplikasi untuk Dosen Penguji</div>
    </div>
    <span class="chip"><i class="fa-solid fa-circle-question"></i> Help Center</span>
  </div>

  <div class="card-bd">

    {{-- SEARCH --}}
    <form method="GET"
          action="{{ route('help.index') }}"
          style="margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;">
      <input type="text"
             name="q"
             class="form-control"
             style="min-width:240px;flex:1"
             placeholder="Cari bantuan... (contoh: penilaian, rubrik, logout)"
             value="{{ $search }}">
      <button class="btn btn-primary" type="submit">
        <i class="fa-solid fa-magnifying-glass"></i> Cari
      </button>
    </form>

    <div class="help-grid">
      {{-- LEFT: CONTENT --}}
      <div>

        {{-- 1) Panduan Singkat --}}
        <div class="faq-item">
          <div class="faq-q">
            <span>🚀 Panduan Singkat Menggunakan Aplikasi</span>
            <i class="fa-solid fa-chevron-down chev"></i>
          </div>
          <div class="faq-a">
            <ol style="margin:0;padding-left:18px">
              <li>Login menggunakan akun yang terdaftar.</li>
              <li>Buka menu <b>Mahasiswa</b> untuk melihat daftar mahasiswa.</li>
              <li>Buka menu <b>Penilaian</b> untuk mengisi nilai.</li>
              <li>Pastikan rubrik dan item penilaian sudah sesuai.</li>
              <li>Klik <b>Simpan</b> setelah mengisi nilai.</li>
            </ol>
          </div>
        </div>

        {{-- 2) Alur Penilaian --}}
        <div class="faq-item">
          <div class="faq-q">
            <span>📋 Alur Penilaian Dosen Penguji</span>
            <i class="fa-solid fa-chevron-down chev"></i>
          </div>
          <div class="faq-a">
            <ul class="list-clean">
              <li>Masuk menu <b>Mahasiswa</b> → pilih mahasiswa/kelas.</li>
              <li>Masuk menu <b>Penilaian</b> → pilih komponen penilaian.</li>
              <li>Isi nilai sesuai kriteria pada <b>Rubrik</b>.</li>
              <li>Jika ada perubahan, lakukan edit lalu <b>Simpan</b> ulang.</li>
              <li>Jika butuh template, gunakan menu export/import (bila tersedia).</li>
            </ul>
          </div>
        </div>

        {{-- 3) Penjelasan Menu --}}
        <div class="faq-item">
          <div class="faq-q">
            <span>🧭 Penjelasan Menu di Aplikasi</span>
            <i class="fa-solid fa-chevron-down chev"></i>
          </div>
          <div class="faq-a">
            <ul class="list-clean">
              <li><b>Dashboard</b>: ringkasan informasi dan aktivitas.</li>
              <li><b>Mahasiswa</b>: daftar mahasiswa yang bisa dinilai.</li>
              <li><b>Kelompok</b>: data kelompok (jika digunakan di sistem).</li>
              <li><b>Penilaian</b>: input/edit nilai dan aksi simpan.</li>
              <li><b>Rubrik</b>: kriteria penilaian yang menjadi acuan.</li>
              <li><b>CPMK</b>: daftar capaian pembelajaran (jika dipakai).</li>
              <li><b>Profil</b>: melihat dan mengubah data akun.</li>
            </ul>
          </div>
        </div>

        {{-- 4) FAQ dari controller (yang sudah kamu punya) --}}
        <div class="faq-item">
          <div class="faq-q">
            <span>❓ FAQ (Pertanyaan Umum)</span>
            <i class="fa-solid fa-chevron-down chev"></i>
          </div>
          <div class="faq-a">
            @if(count($items))
              @foreach($items as $it)
                <div style="margin-bottom:12px">
                  <div style="font-weight:900;color:#0e257a">{{ $it['q'] }}</div>
                  <ul class="list-clean">
                    @foreach($it['a'] as $line)
                      <li>{{ $line }}</li>
                    @endforeach
                  </ul>
                </div>
              @endforeach
            @else
              <div class="mini">Belum ada data FAQ.</div>
            @endif
          </div>
        </div>

        {{-- 5) Troubleshooting --}}
        <div class="faq-item">
          <div class="faq-q">
            <span>🛠 Masalah Umum & Solusinya</span>
            <i class="fa-solid fa-chevron-down chev"></i>
          </div>
          <div class="faq-a">
            <ul class="list-clean">
              <li><b>Nilai tidak tersimpan</b> → pastikan semua kolom wajib terisi, lalu klik simpan.</li>
              <li><b>Halaman kosong / error</b> → refresh halaman, logout lalu login kembali.</li>
              <li><b>Tidak bisa login</b> → cek email & password, pastikan internet stabil.</li>
              <li><b>Data mahasiswa tidak muncul</b> → pastikan Anda sudah terdaftar sebagai dosen penguji pada kelas tersebut.</li>
            </ul>
          </div>
        </div>

        {{-- 6) Kebijakan --}}
        <div class="faq-item">
          <div class="faq-q">
            <span>🔐 Kebijakan Penggunaan</span>
            <i class="fa-solid fa-chevron-down chev"></i>
          </div>
          <div class="faq-a">
            <ul class="list-clean">
              <li>Akun bersifat pribadi dan tidak boleh dibagikan.</li>
              <li>Gunakan rubrik sebagai acuan penilaian.</li>
              <li>Jika ada masalah sistem, hubungi admin melalui kontak resmi.</li>
            </ul>
          </div>
        </div>

      </div>

      {{-- RIGHT: CONTACT --}}
      <div>
        <div class="card" style="margin-bottom:16px">
          <div class="card-hd">Kontak Bantuan</div>
          <div class="card-bd">
            <div style="display:grid;gap:10px">
              <div>
                <div style="font-weight:900;color:#0e257a"><i class="fa-solid fa-envelope"></i> Email</div>
                <div class="mini">support@domain.com</div>
              </div>
              <div>
                <div style="font-weight:900;color:#0e257a"><i class="fa-brands fa-whatsapp"></i> WhatsApp</div>
                <div class="mini">08xxxxxxxxxx</div>
              </div>
              <div>
                <div style="font-weight:900;color:#0e257a"><i class="fa-solid fa-clock"></i> Jam Operasional</div>
                <div class="mini">Senin–Jumat, 08.00–16.00</div>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-hd">Catatan</div>
          <div class="card-bd">
            <div class="mini">
              Jika butuh bantuan lebih cepat, kirim screenshot error + langkah yang dilakukan.
            </div>
          </div>
        </div>
      </div>

    </div> {{-- grid --}}
  </div> {{-- card-bd --}}
</div>

<script>
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.faq-q').forEach(q => {
      q.addEventListener('click', () => q.parentElement.classList.toggle('open'));
    });
  });
</script>
@endsection
