{{-- resources/views/dosenpenguji/cpmk.blade.php --}}
@extends('dosenpenguji.layout')
@section('title', 'CPMK — Dosen Penguji')
@section('header', 'CPMK (Capaian Pembelajaran Mata Kuliah)')

@section('content')
<style>
  .filters{display:flex; gap:10px; align-items:center; flex-wrap:wrap}
  .chip{display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; background:#eef2ff; color:#22336b; font-weight:700; font-size:13px}
  .empty{display:flex; align-items:center; gap:12px; padding:14px 16px; color:#667085; background:#f8fafc; border:1px dashed #e5e7eb; border-radius:12px}
  .table th, .table td{padding:10px 12px; border-bottom:1px solid #eef1f6}
  .table th{color:#0e257a; font-weight:800; background:#f6f8fd}
  .right{text-align:right} .center{text-align:center}
</style>

<div class="card">
  <div class="card-hd">
    <div class="filters" style="justify-content:space-between; width:100%">
      <div class="filters">
        <div class="fw-semibold" style="min-width:92px">Mata Kuliah:</div>

        {{-- filter MK --}}
        <form method="GET" action="{{ url('/dosenpenguji/cpmk') }}" class="filters">
          <select name="matakuliah" class="form-control" style="min-width:220px" onchange="this.form.submit()">
            <option value="">Pilih MK</option>
            @foreach(($matakuliah ?? collect()) as $mkrow)
              <option value="{{ $mkrow->kode_mk }}" @selected(($mk ?? '') === $mkrow->kode_mk)>
                {{ $mkrow->nama_mk }}
              </option>
            @endforeach
          </select>
          @if(!empty($mk))
            <span class="chip">
              <i class="fa-solid fa-book"></i>
              {{ $matakuliah->firstWhere('kode_mk',$mk)->nama_mk ?? $mk }}
            </span>
          @endif
        </form>
      </div>

      {{-- total bobot + tombol tambah --}}
      <div class="filters">
        @php
          $totalBobot = ($cpmk ?? collect())->sum('bobot');
        @endphp
        <div class="chip" title="Total bobot semua CPMK">
          <i class="fa-solid fa-percent"></i> Total Bobot: {{ number_format($totalBobot,0) }}%
        </div>

        @if(!empty($mk))
          <button type="button" id="btnOpenCreate" class="btn btn-primary">
            + Tambah CPMK
          </button>
        @endif
      </div>
    </div>
  </div>

  <div class="card-bd">
    @if(isset($cpmk) && $cpmk->count())
      <div class="table-responsive">
        <table class="table align-middle" style="width:100%">
          <thead>
            <tr>
              <th style="width:25%">Kode CPMK</th>
              <th>Deskripsi CPMK</th>
              <th class="center" style="width:15%">Bobot (%)</th>
              <th class="center" style="width:15%">Urutan</th>
              <th class="right" style="width:20%">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($cpmk as $c)
            <tr>
              <td><strong>{{ $c->kode }}</strong></td>
              <td>{{ $c->deskripsi }}</td>
              <td class="center">{{ $c->bobot }}</td>
              <td class="center">{{ $c->urutan }}</td>
              <td class="right">
                {{-- Tombol EDIT: buka modal & isi data --}}
                <button
                  type="button"
                  class="btn btn-secondary js-edit"
                  data-kode="{{ $c->kode }}"
                  data-mk="{{ $mk }}"
                  data-deskripsi='@json($c->deskripsi)'
                  data-bobot="{{ $c->bobot }}"
                  data-urutan="{{ $c->urutan }}">
                  <i class="fa-solid fa-pen"></i> Edit
                </button>

                {{-- DETAIL: modal readonly --}}
                <button
                  type="button"
                  class="btn btn-primary js-detail"
                  data-kode="{{ $c->kode }}"
                  data-deskripsi='@json($c->deskripsi)'
                  data-bobot="{{ $c->bobot }}"
                  data-urutan="{{ $c->urutan }}">
                  <i class="fa-solid fa-eye"></i> Detail
                </button>

                {{-- HAPUS CPMK --}}
                <form
                  action="{{ url('/dosenpenguji/cpmk/'.$c->id) }}"
                  method="POST"
                  style="display:inline-block; margin-left:6px;"
                  onsubmit="return confirm('Yakin ingin menghapus CPMK {{ $c->kode }} ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i> Hapus
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
          </tbody>

        </table>
      </div>
    @elseif(!empty($mk))
      {{-- MK sudah dipilih tapi CPMK masih kosong --}}
      <div class="empty">
        <i class="fa-solid fa-circle-info"></i>
        <div>
          <div class="fw-bold">Belum ada data CPMK untuk mata kuliah ini.</div>
          <div style="font-size:13px">Klik tombol <b>Tambah CPMK</b> untuk menambahkan.</div>
        </div>
      </div>
    @else
      {{-- MK belum dipilih --}}
      <div class="empty">
        <i class="fa-solid fa-circle-info"></i>
        <div>
          <div class="fw-bold">Belum ada data CPMK</div>
          <div style="font-size:13px">Pilih Mata Kuliah terlebih dahulu untuk menampilkan daftar CPMK.</div>
        </div>
      </div>
    @endif
  </div>
</div>

<style>
  .modal-backdrop{position:fixed;inset:0;background:#0008;display:none;align-items:center;justify-content:center;z-index:50}
  .modal-card{width:680px;max-width:95vw;background:#fff;border-radius:14px;box-shadow:0 15px 60px rgba(16,24,40,.18);overflow:hidden}
  .modal-hd{padding:14px 18px;border-bottom:1px solid #eef1f6;font-weight:800;color:#0e257a}
  .modal-bd{padding:16px 18px}
  .modal-ft{padding:12px 18px;border-top:1px solid #eef1f6;display:flex;gap:10px;justify-content:flex-end}
  .form-row{display:grid;grid-template-columns:1fr 160px 120px;gap:12px}
  .form-row label{font-size:13px;color:#475569;margin-bottom:6px;display:block}
</style>

{{-- ===== MODAL TAMBAH (CREATE) ===== --}}
<div id="createModal" class="modal-backdrop">
  <div class="modal-card">
    <div class="modal-hd">Tambah CPMK</div>
    <form id="createForm" method="POST" action="{{ url('/dosenpenguji/cpmk') }}">
      @csrf
      <div class="modal-bd">
        <input type="hidden" name="kode_mk" value="{{ $mk }}">
        <div class="form-row">
          <div class="form-group">
            <label>Kode CPMK</label>
            <input name="kode" type="text" class="form-control" placeholder="Misal: CPMK1" required>
          </div>
          <div class="form-group">
            <label>Bobot (%)</label>
            <input name="bobot" type="number" min="0" max="100" class="form-control" value="0" required>
          </div>
          <div class="form-group">
            <label>Urutan</label>
            <input name="urutan" type="number" min="1" class="form-control" value="1" required>
          </div>
        </div>
        <div class="form-group mt-3">
          <label>Deskripsi CPMK</label>
          <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
        </div>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn btn-secondary" id="btnCancelCreate">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- ===== MODAL EDIT ===== --}}
<div id="editModal" class="modal-backdrop">
  <div class="modal-card">
    <div class="modal-hd">Edit CPMK</div>
    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-bd">
        <input type="hidden" id="em_kode_mk" name="kode_mk">
        <input type="hidden" id="em_kode" name="kode">
        <div class="form-group">
          <label>Kode CPMK</label>
          <input id="em_kode_view" type="text" class="form-control" readonly>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Deskripsi CPMK</label>
            <textarea id="em_deskripsi" name="deskripsi" class="form-control" rows="4" required></textarea>
          </div>
          <div class="form-group">
            <label>Bobot (%)</label>
            <input id="em_bobot" name="bobot" type="number" min="0" max="100" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Urutan</label>
            <input id="em_urutan" name="urutan" type="number" min="1" class="form-control" required>
          </div>
        </div>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn btn-secondary" id="btnCancel">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- ===== MODAL DETAIL ===== --}}
<div id="detailModal" class="modal-backdrop">
  <div class="modal-card">
    <div class="modal-hd">Detail CPMK</div>
    <div class="modal-bd">
      <div class="form-group">
        <label>Kode CPMK</label>
        <input id="dm_kode" type="text" class="form-control" readonly>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Deskripsi CPMK</label>
          <textarea id="dm_deskripsi" class="form-control" rows="4" readonly></textarea>
        </div>
        <div class="form-group">
          <label>Bobot (%)</label>
          <input id="dm_bobot" type="text" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label>Urutan</label>
          <input id="dm_urutan" type="text" class="form-control" readonly>
        </div>
      </div>
    </div>
    <div class="modal-ft">
      <button type="button" class="btn btn-primary" id="btnCloseDetail">Tutup</button>
    </div>
  </div>
</div>

<script>
  (function(){
    // ====== MODAL CREATE ======
    const createModal  = document.getElementById('createModal');
    const btnOpenCreate = document.getElementById('btnOpenCreate');
    const btnCancelCreate = document.getElementById('btnCancelCreate');

    function openCreate(){ createModal.style.display='flex'; }
    function closeCreate(){ createModal.style.display='none'; }

    if (btnOpenCreate) {
      btnOpenCreate.addEventListener('click', openCreate);
    }
    if (btnCancelCreate) {
      btnCancelCreate.addEventListener('click', closeCreate);
    }
    if (createModal) {
      createModal.addEventListener('click', (e)=>{ if(e.target===createModal) closeCreate(); });
    }

    // ====== MODAL EDIT ======
    const modal  = document.getElementById('editModal');
    const form   = document.getElementById('editForm');

    function openModal(){ modal.style.display='flex'; }
    function closeModal(){ modal.style.display='none'; }

    document.querySelectorAll('.js-edit').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const kode   = btn.dataset.kode;
        const mk     = btn.dataset.mk;
        const desk   = JSON.parse(btn.dataset.deskripsi || '""');
        const bobot  = btn.dataset.bobot || '';
        const urutan = btn.dataset.urutan || '';

        // set action ke route quickUpdate: PUT /dosenpenguji/cpmk/{mk}/{kode}
        form.action = `/dosenpenguji/cpmk/${encodeURIComponent(mk)}/${encodeURIComponent(kode)}`;

        // isi field
        document.getElementById('em_kode_mk').value = mk;
        document.getElementById('em_kode').value    = kode;
        document.getElementById('em_kode_view').value = kode;
        document.getElementById('em_deskripsi').value = desk;
        document.getElementById('em_bobot').value     = bobot;
        document.getElementById('em_urutan').value    = urutan;

        openModal();
      });
    });

    document.getElementById('btnCancel').addEventListener('click', closeModal);
    modal.addEventListener('click', (e)=>{ if(e.target===modal) closeModal(); });

    // ===== DETAIL (readonly) =====
    const dModal = document.getElementById('detailModal');
    const openD  = () => dModal.style.display='flex';
    const closeD = () => dModal.style.display='none';

    document.querySelectorAll('.js-detail').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        document.getElementById('dm_kode').value      = btn.dataset.kode || '';
        document.getElementById('dm_deskripsi').value = JSON.parse(btn.dataset.deskripsi || '""');
        document.getElementById('dm_bobot').value     = btn.dataset.bobot || '';
        document.getElementById('dm_urutan').value    = btn.dataset.urutan || '';
        openD();
      });
    });

    document.getElementById('btnCloseDetail').addEventListener('click', closeD);
    dModal.addEventListener('click', (e)=>{ if(e.target===dModal) closeD(); });
  })();
</script>

@endsection
