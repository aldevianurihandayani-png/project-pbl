@extends('layouts.koordinator')

@section('title', 'Proyek PBL — Koordinator')
@section('page_title', 'Proyek PBL')

@push('styles')
<style>
  /* ====== Page header kecil ====== */
  .page-head{
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    margin-bottom:12px;
  }
  .page-head .subtitle{ font-size:12px; color:var(--muted); margin-top:4px; }

  /* ====== Button (biar nyatu sama theme) ====== */
  .btn{
    display:inline-flex; align-items:center; gap:8px;
    padding:10px 12px; border-radius:12px; border:1px solid transparent;
    font-weight:700; font-size:13px; cursor:pointer; text-decoration:none;
  }
  .btn-primary{ background:#1c3d86; color:#fff; }
  .btn-primary:hover{ filter:brightness(1.05); }
  .btn-sm{ padding:7px 10px; border-radius:10px; font-size:12px; }
  .btn-warning{ background:#fef9c3; color:#854d0e; border:1px solid #fde68a; }
  .btn-danger{ background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; }

  /* ====== Alert ====== */
  .alert{
    padding:12px 14px; border-radius:14px; border:1px solid;
    font-size:13px; font-weight:600;
  }
  .alert-success{ background:#ecfdf5; border-color:#bbf7d0; color:#166534; }
  .alert-danger{ background:#fef2f2; border-color:#fecaca; color:#991b1b; }

  /* ====== Table modern ====== */
  .table-wrap{ overflow:auto; }
  .table-modern{
    width:100%; border-collapse:separate; border-spacing:0;
    font-size:13px;
  }
  .table-modern thead th{
    background:#f7f9ff;
    color:#51607a;
    text-transform:uppercase;
    letter-spacing:.06em;
    font-size:11px;
    padding:10px 12px;
    border-bottom:1px solid #e3e7f2;
    text-align:left;
    white-space:nowrap;
  }
  .table-modern tbody td{
    padding:10px 12px;
    border-bottom:1px solid #f0f2f8;
    white-space:nowrap;
    vertical-align:top;
  }
  .table-modern tbody tr:hover{ background:#f7f8fe; }

  .pill{
    display:inline-flex; align-items:center;
    font-size:12px; padding:4px 10px; border-radius:999px;
    background:#eef3ff; color:#273b90; border:1px solid #dde6ff;
    white-space:nowrap;
  }
  .judul-cell{
    white-space:normal !important;
    min-width:280px;
    font-weight:700;
    color:var(--navy-2);
  }

  /* pagination bawaan bootstrap kadang jelek, minimal spacing */
  .pagination{ margin:12px 0 0; }
</style>
@endpush

@section('content')

  <div class="page-head">
    <div>
      <div style="font-weight:800;color:var(--navy-2);font-size:16px;">Daftar Proyek PBL</div>
      <div class="subtitle">Kelola data proyek sesuai tabel <b>proyek_pbl</b></div>
    </div>

    <a href="{{ route('koordinator.proyek-pbl.create') }}" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Tambah Proyek
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
  @endif

  <section class="card">
    <div class="card-hd">
      <div style="display:flex;align-items:center;gap:10px">
        <i class="fa-solid fa-diagram-project"></i>
        <span>Data Proyek</span>
        <span class="pill">{{ $items->total() }} data</span>
      </div>
      <span class="small">Klik Edit untuk ubah data</span>
    </div>

    <div class="card-bd">
      <div class="table-wrap">
        <table class="table-modern">
          <thead>
            <tr>
              <th>ID</th>
              <th>Judul</th>
              <th>Tanggal</th>
              <th>Kode MK</th>
              <th>ID Dosen</th>
              <th>ID Kelompok</th>
              <th style="width:170px">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($items as $row)
              <tr>
                <td><span class="pill">#{{ $row->id_proyek_pbl }}</span></td>

                <td class="judul-cell">
                  {{ $row->judul }}
                </td>

                <td>
                  @if(!empty($row->tanggal))
                    {{ \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d') }}
                  @else
                    <span class="muted">-</span>
                  @endif
                </td>

                <td>{{ $row->kode_mk }}</td>
                <td>{{ $row->id_dosen }}</td>
                <td>{{ $row->id_kelompok }}</td>

                <td>
                  <a class="btn btn-sm btn-warning"
                     href="{{ route('koordinator.proyek-pbl.edit', $row->id_proyek_pbl) }}">
                    <i class="fa-solid fa-pen"></i> Edit
                  </a>

                  <form method="POST"
                        action="{{ route('koordinator.proyek-pbl.destroy', $row->id_proyek_pbl) }}"
                        onsubmit="return confirm('Yakin hapus proyek ini?')"
                        style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" type="submit">
                      <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="muted" style="padding:14px 12px;">
                  Belum ada data proyek.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div style="margin-top:12px;">
        {{ $items->links() }}
      </div>
    </div>
  </section>

@endsection
