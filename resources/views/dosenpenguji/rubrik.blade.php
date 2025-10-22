{{-- resources/views/dosenpenguji/rubrik.blade.php --}}
@extends('dosenpenguji.layout')
@section('title', 'Rubrik — Dosen Penguji')
@section('header', 'Rubrik Penilaian')

@section('content')
<style>
  .filters{display:flex; gap:10px; align-items:center; flex-wrap:wrap}
  .chip{display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; background:#eef2ff; color:#22336b; font-weight:700; font-size:13px}
  .empty{display:flex; align-items:center; gap:12px; padding:14px 16px; color:#667085; background:#f8fafc; border:1px dashed #e5e7eb; border-radius:12px}
  .table th, .table td{padding:10px 12px; border-bottom:1px solid #eef1f6}
  .table th{color:#0e257a; font-weight:800; background:#f6f8fd}
  .right{text-align:right} .center{text-align:center}

  /* modal (ringan, tanpa ubah tema) */
  .modal-backdrop{position:fixed;inset:0;background:#0008;display:none;align-items:center;justify-content:center;z-index:50}
  .modal-card{width:720px;max-width:95vw;background:#fff;border-radius:14px;box-shadow:0 15px 60px rgba(16,24,40,.18);overflow:hidden}
  .modal-hd{padding:14px 18px;border-bottom:1px solid #eef1f6;font-weight:800;color:#0e257a}
  .modal-bd{padding:16px 18px}
  .modal-ft{padding:12px 18px;border-top:1px solid #eef1f6;display:flex;gap:10px;justify-content:flex-end}
  .grid-2{display:grid;grid-template-columns:1fr 200px 120px;gap:12px}
  .form-control{border:1px solid #d0d7e7;border-radius:8px;padding:8px 10px}
  .form-control:focus{border-color:#0e257a;box-shadow:0 0 0 2px rgba(14,37,122,.15)}
</style>

<div class="card">
  <div class="card-hd">
    <div class="filters">
      <div class="fw-semibold" style="min-width:92px">Mata Kuliah:</div>
      <form method="GET" action="{{ route('dosenpenguji.rubrik.index') }}" class="filters">
        <select name="matakuliah" class="form-control" style="min-width:220px" onchange="this.form.submit()">
          <option value="">Pilih MK</option>
          @foreach(($matakuliah ?? collect()) as $mkrow)
            <option value="{{ $mkrow->kode_mk }}" @selected(($mk ?? '') === $mkrow->kode_mk)>{{ $mkrow->nama_mk }}</option>
          @endforeach
        </select>
        @if(!empty($mk))
          <span class="chip"><i class="fa-solid fa-book"></i> {{ $matakuliah->firstWhere('kode_mk',$mk)->nama_mk ?? $mk }}</span>
        @endif
      </form>
    </div>

    @php $totalBobot = ($rubriks ?? collect())->sum('bobot'); @endphp
    <div class="chip" title="Total bobot semua komponen">
      <i class="fa-solid fa-percent"></i> Total Bobot: {{ number_format($totalBobot,0) }}%
    </div>
  </div>

  <div class="card-bd">
    @if(isset($rubriks) && $rubriks->count())
      <div class="table-responsive">
        <table class="table align-middle" style="width:100%">
          <thead>
            <tr>
              <th style="width:50%">Nama Komponen</th>
              <th class="center" style="width:15%">Bobot (%)</th>
              <th class="center" style="width:15%">Urutan</th>
              <th class="right"  style="width:20%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($rubriks as $r)
              @php
                // ambil id yang tersedia (id / rubric_id / id_rubrik)
                $rid = $r->id ?? $r->rubric_id ?? $r->id_rubrik;
              @endphp
              <tr>
                <td>
                  <strong>{{ $r->nama_rubrik }}</strong>
                  @if(!empty($r->deskripsi))
                    <div style="color:#6b7280; font-size:13px">{{ $r->deskripsi }}</div>
                  @endif
                </td>
                <td class="center">{{ $r->bobot }}</td>
                <td class="center">{{ $r->urutan }}</td>
                <td class="right">
                  {{-- EDIT: buka modal & isi data --}}
                  <button
                    type="button"
                    class="btn btn-secondary js-edit"
                    data-id="{{ $rid }}"
                    data-mk="{{ $mk }}"
                    data-nama='@json($r->nama_rubrik)'
                    data-deskripsi='@json($r->deskripsi)'
                    data-bobot="{{ $r->bobot }}"
                    data-urutan="{{ $r->urutan }}">
                    <i class="fa-solid fa-pen"></i> Edit
                  </button>

                  {{-- DETAIL: modal readonly --}}
                  <button
                    type="button"
                    class="btn btn-primary js-detail"
                    data-nama='@json($r->nama_rubrik)'
                    data-deskripsi='@json($r->deskripsi)'
                    data-bobot="{{ $r->bobot }}"
                    data-urutan="{{ $r->urutan }}">
                    <i class="fa-solid fa-eye"></i> Detail
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if (method_exists($rubriks, 'hasPages') && $rubriks->hasPages())
        <div class="mt-2">{{ $rubriks->links() }}</div>
      @endif
    @else
      <div class="empty">
        <i class="fa-solid fa-circle-info"></i>
        <div>
          <div class="fw-bold">Belum ada data rubrik</div>
          <div style="font-size:13px">Pilih Mata Kuliah terlebih dahulu untuk menampilkan komponen penilaian.</div>
        </div>
      </div>
    @endif
  </div>
</div>

{{-- ===== MODAL EDIT ===== --}}
<div id="editModal" class="modal-backdrop">
  <div class="modal-card">
    <div class="modal-hd">Edit Komponen Rubrik</div>
    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-bd">
        <input type="hidden" id="er_id" name="id">
        <input type="hidden" id="er_mk" name="kode_mk">
        <div class="form-group">
          <label>Nama Komponen</label>
          <input id="er_nama" name="nama_rubrik" type="text" class="form-control" required>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea id="er_deskripsi" name="deskripsi" class="form-control" rows="4"></textarea>
          </div>
          <div class="form-group">
            <label>Bobot (%)</label>
            <input id="er_bobot" name="bobot" type="number" class="form-control" min="0" max="100" required>
          </div>
          <div class="form-group">
            <label>Urutan</label>
            <input id="er_urutan" name="urutan" type="number" class="form-control" min="1" required>
          </div>
        </div>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn btn-secondary" id="btnCancelEdit">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- ===== MODAL DETAIL ===== --}}
<div id="detailModal" class="modal-backdrop">
  <div class="modal-card">
    <div class="modal-hd">Detail Komponen Rubrik</div>
    <div class="modal-bd">
      <div class="form-group">
        <label>Nama Komponen</label>
        <input id="dr_nama" type="text" class="form-control" readonly>
      </div>
      <div class="grid-2">
        <div class="form-group">
          <label>Deskripsi</label>
          <textarea id="dr_deskripsi" class="form-control" rows="4" readonly></textarea>
        </div>
        <div class="form-group">
          <label>Bobot (%)</label>
          <input id="dr_bobot" type="text" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label>Urutan</label>
          <input id="dr_urutan" type="text" class="form-control" readonly>
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
  const mEdit   = document.getElementById('editModal');
  const mDetail = document.getElementById('detailModal');
  const fEdit   = document.getElementById('editForm');

  const open   = m => m.style.display = 'flex';
  const close  = m => m.style.display = 'none';

  // === Edit
  document.querySelectorAll('.js-edit').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id   = btn.dataset.id;
      const mk   = btn.dataset.mk || '';
      const nama = JSON.parse(btn.dataset.nama || '""');
      const desk = JSON.parse(btn.dataset.deskripsi || '""');
      const bobot= btn.dataset.bobot || '';
      const urut = btn.dataset.urutan || '';

      fEdit.action = `/dosenpenguji/rubrik/${encodeURIComponent(id)}`; // route PUT
      document.getElementById('er_id').value        = id;
      document.getElementById('er_mk').value        = mk;
      document.getElementById('er_nama').value      = nama;
      document.getElementById('er_deskripsi').value = desk;
      document.getElementById('er_bobot').value     = bobot;
      document.getElementById('er_urutan').value    = urut;

      open(mEdit);
    });
  });
  document.getElementById('btnCancelEdit').addEventListener('click', ()=>close(mEdit));
  mEdit.addEventListener('click', e=>{ if(e.target===mEdit) close(mEdit); });

  // === Detail
  document.querySelectorAll('.js-detail').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      document.getElementById('dr_nama').value      = JSON.parse(btn.dataset.nama || '""');
      document.getElementById('dr_deskripsi').value = JSON.parse(btn.dataset.deskripsi || '""');
      document.getElementById('dr_bobot').value     = btn.dataset.bobot || '';
      document.getElementById('dr_urutan').value    = btn.dataset.urutan || '';
      open(mDetail);
    });
  });
  document.getElementById('btnCloseDetail').addEventListener('click', ()=>close(mDetail));
  mDetail.addEventListener('click', e=>{ if(e.target===mDetail) close(mDetail); });
})();
</script>
@endsection
