@extends('layouts.admin')

@section('page_title', 'Edit Notifikasi')

@section('content')

<style>
  /* ✅ FULL WIDTH (rata kiri–kanan) */
  .nf-wrap{ width:100%; margin:0; }

  .nf-card{
    background:#fff;
    border:1px solid rgba(13,23,84,.10);
    border-radius:18px;
    box-shadow:0 6px 18px rgba(13,23,84,.08);
    overflow:hidden;
  }

  /* ✅ Body lebih rapi */
  .nf-body{ padding:22px 24px; }

  .nf-grid{ display:grid; grid-template-columns:1fr; gap:14px; }
  @media(min-width:992px){ .nf-grid.two{ grid-template-columns:1fr 1fr; } }

  .nf-label{
    font-weight:900;
    font-size:13px;
    color:#0b1d54;
    margin-bottom:7px;
    display:flex;
    align-items:center;
    gap:8px;
  }

  .nf-input,.nf-select,.nf-textarea{
    width:100%;
    border:1px solid rgba(13,23,84,.14);
    border-radius:14px;
    padding:11px 12px;
    outline:none;
    background:#fff;
    font-size:14px;
    transition: box-shadow .15s ease, border-color .15s ease;
  }
  .nf-input:focus,.nf-select:focus,.nf-textarea:focus{
    border-color: var(--navy-2);
    box-shadow: 0 0 0 4px rgba(14,37,122,.12);
  }
  .nf-textarea{ min-height:120px; resize:vertical; line-height:1.4; }

  .nf-help{ font-size:12px; color:#6c7a8a; margin-top:7px; }

  .is-invalid{
    border-color:#e53935 !important;
    box-shadow:0 0 0 4px rgba(229,57,53,.10) !important;
  }
  .invalid-feedback{ display:block; margin-top:6px; font-size:12px; color:#e53935; font-weight:700; }

  .nf-alert{
    border-radius:16px;
    padding:12px 14px;
    border:1px solid rgba(13,23,84,.12);
    background:#fff;
    margin-bottom:14px;
  }
  .nf-alert.success{ border-color: rgba(0,177,103,.35); background: rgba(0,177,103,.06); }
  .nf-alert.danger{ border-color: rgba(229,57,53,.35); background: rgba(229,57,53,.06); }
  .nf-alert h6{ margin:0 0 6px; font-weight:900; color:#0b1d54; }
  .nf-alert ul{ margin:0; padding-left:18px; }

  .nf-divider{ height:1px; background: rgba(13,23,84,.08); margin:16px 0; }

  /* Preview */
  .nf-preview{
    margin-top:14px;
    border:1px solid rgba(13,23,84,.12);
    border-radius:16px;
    background: linear-gradient(180deg, #fbfcff 0%, #ffffff 100%);
    overflow:hidden;
  }
  .nf-preview-hd{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    padding:10px 12px;
    border-bottom:1px solid rgba(13,23,84,.08);
  }
  .nf-chip{
    display:inline-flex;
    align-items:center;
    gap:7px;
    padding:6px 10px;
    border-radius:999px;
    background: rgba(14,37,122,.10);
    color: var(--navy-2);
    font-size:12px;
    font-weight:900;
  }
  .nf-preview-bd{ padding:12px; }
  .nf-p-title{ margin:0; font-weight:900; color:#0b1d54; font-size:14px; }
  .nf-p-msg{ margin:7px 0 0; color:#42526e; font-size:13px; line-height:1.45; }
  .nf-p-meta{ margin-top:10px; font-size:12px; color:#6c7a8a; display:flex; gap:10px; flex-wrap:wrap; }

  /* Actions */
  .nf-actions{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    flex-wrap:wrap;
    margin-top:16px;
  }
  .btn-nf{
    border:0;
    border-radius:14px;
    padding:11px 14px;
    font-weight:900;
    font-size:13px;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:8px;
    text-decoration:none;
    transition: transform .12s ease, background .12s ease;
  }
  .btn-nf:active{ transform: translateY(1px); }
  .btn-nf.primary{ background: var(--navy-2); color:#fff; }
  .btn-nf.primary:hover{ background: var(--navy); }
  .btn-nf.secondary{ background:#e9eefb; color: var(--navy-2); }
  .btn-nf.secondary:hover{ background:#dde6fb; }
</style>

<div class="nf-wrap">
  <div class="nf-card">

    <div class="nf-body">

      @if (session('success'))
        <div class="nf-alert success">
          <h6><i class="fa-solid fa-check-circle"></i> Berhasil</h6>
          <div>{{ session('success') }}</div>
        </div>
      @endif

      @if ($errors->any())
        <div class="nf-alert danger">
          <h6><i class="fa-solid fa-triangle-exclamation"></i> Terdapat Kesalahan</h6>
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admins.notifikasi.update', $notifikasi) }}" method="POST" id="notifForm">
        @csrf
        @method('PUT')

        <div class="nf-grid">
          <div>
            <label class="nf-label" for="judul">
              <i class="fa-solid fa-heading"></i> Judul Notifikasi
            </label>
            <input
              type="text"
              id="judul"
              name="judul"
              class="nf-input @error('judul') is-invalid @enderror"
              value="{{ old('judul', $notifikasi->judul) }}"
              placeholder="Contoh: Pengumpulan Laporan PBL"
              required
            >
            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="nf-help">Judul akan terlihat jelas di list notifikasi dan dropdown lonceng.</div>
          </div>

          <div>
            <label class="nf-label" for="pesan">
              <i class="fa-solid fa-message"></i> Pesan
            </label>
            <textarea
              id="pesan"
              name="pesan"
              class="nf-textarea @error('pesan') is-invalid @enderror"
              placeholder="Isi pesan notifikasi (opsional)…"
            >{{ old('pesan', $notifikasi->pesan) }}</textarea>
            @error('pesan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="nf-help">Opsional. Jika diisi, tampil di bawah judul.</div>
          </div>
        </div>

        <div class="nf-divider"></div>

        <div class="nf-grid two">
          <div>
            <label class="nf-label" for="type">
              <i class="fa-solid fa-tag"></i> Tipe
            </label>
            <select id="type" name="type" class="nf-select @error('type') is-invalid @enderror">
              <option value="info"   {{ old('type', $notifikasi->type) == 'info' ? 'selected' : '' }}>Informasi</option>
              <option value="materi" {{ old('type', $notifikasi->type) == 'materi' ? 'selected' : '' }}>Materi</option>
              <option value="tugas"  {{ old('type', 'info') == 'tugas' ? 'selected' : '' }}>Tugas</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div>
            <label class="nf-label" for="link_url">
              <i class="fa-solid fa-link"></i> URL Tautan
            </label>
            <input
              type="url"
              id="link_url"
              name="link_url"
              class="nf-input @error('link_url') is-invalid @enderror"
              value="{{ old('link_url', $notifikasi->link_url) }}"
              placeholder="https://example.com (opsional)"
            >
            @error('link_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="nf-help">Opsional. Kalau diisi, user bisa klik langsung dari dropdown lonceng.</div>
          </div>
        </div>

        <div style="height:12px"></div>

        <div>
          <label class="nf-label" for="user_id">
            <i class="fa-solid fa-users"></i> Penerima
          </label>
          <select id="user_id" name="user_id" class="nf-select @error('user_id') is-invalid @enderror">
            <option value="">— Kirim ke Semua Pengguna —</option>
            @foreach ($users as $user)
              <option value="{{ $user->id }}" {{ old('user_id', $notifikasi->user_id) == $user->id ? 'selected' : '' }}>
                {{ $user->nama ?? $user->name }} ({{ $user->role ?? '-' }})
              </option>
            @endforeach
          </select>
          @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="nf-help">Kosongkan untuk broadcast ke semua user.</div>
        </div>

        {{-- Preview --}}
        <div class="nf-preview" id="previewBox">
          <div class="nf-preview-hd">
            <span class="nf-chip" id="previewChip"><i class="fa-solid fa-bell"></i> Informasi</span>
            <span style="font-size:12px;color:#6c7a8a;font-weight:800" id="previewRecipient">
              Kepada: Semua Pengguna
            </span>
          </div>
          <div class="nf-preview-bd">
            <p class="nf-p-title" id="previewTitle">{{ old('judul', $notifikasi->judul) ?: 'Judul notifikasi akan tampil di sini' }}</p>
            <p class="nf-p-msg" id="previewMsg">{{ old('pesan', $notifikasi->pesan) ?: 'Pesan (opsional) akan tampil di sini.' }}</p>
            <div class="nf-p-meta">
              <span id="previewUrl" style="display:none;">
                <i class="fa-solid fa-link"></i> <span id="previewUrlText"></span>
              </span>
            </div>
          </div>
        </div>

        <div class="nf-actions">
          <a href="{{ route('admins.notifikasi.index') }}" class="btn-nf secondary">
            <i class="fa-solid fa-arrow-left"></i> Batal
          </a>
          <button type="submit" class="btn-nf primary">
            <i class="fa-solid fa-save"></i> Simpan Perubahan
          </button>
        </div>

      </form>
    </div>

  </div>
</div>

<script>
  (function(){
    const judul = document.getElementById('judul');
    const pesan = document.getElementById('pesan');
    const type  = document.getElementById('type');
    const url   = document.getElementById('link_url');
    const user  = document.getElementById('user_id');

    const pTitle = document.getElementById('previewTitle');
    const pMsg   = document.getElementById('previewMsg');
    const pChip  = document.getElementById('previewChip');
    const pRec   = document.getElementById('previewRecipient');
    const pUrl   = document.getElementById('previewUrl');
    const pUrlTx = document.getElementById('previewUrlText');

    function chipIcon(val){
      if(val === 'tugas')  return '<i class="fa-solid fa-clipboard-check"></i> Tugas';
      if(val === 'materi') return '<i class="fa-solid fa-book-open"></i> Materi';
      return '<i class="fa-solid fa-bell"></i> Informasi';
    }

    function update(){
      pTitle.textContent = (judul.value || '').trim() || 'Judul notifikasi akan tampil di sini';
      pMsg.textContent   = (pesan.value || '').trim() || 'Pesan (opsional) akan tampil di sini.';
      pChip.innerHTML    = chipIcon(type.value);

      const selected = user.options[user.selectedIndex];
      pRec.textContent = user.value
        ? 'Kepada: ' + (selected ? selected.text : 'User')
        : 'Kepada: Semua Pengguna';

      const u = (url.value || '').trim();
      if (u) {
        pUrl.style.display = '';
        pUrlTx.textContent = u;
      } else {
        pUrl.style.display = 'none';
        pUrlTx.textContent = '';
      }
    }

    ['input','change'].forEach(evt=>{
      judul.addEventListener(evt, update);
      pesan.addEventListener(evt, update);
      type.addEventListener(evt, update);
      url.addEventListener(evt, update);
      user.addEventListener(evt, update);
    });

    // Initial call to populate preview
    update();
  })();
</script>

@endsection
