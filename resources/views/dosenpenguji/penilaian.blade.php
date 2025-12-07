{{-- resources/views/dosenpenguji/penilaian.blade.php --}}
@extends('dosenpenguji.layout')

@section('title', 'Penilaian — Dosen Penguji')
@section('header', 'Penilaian')

@section('content')
<style>
  /* ===== STYLE KHUSUS HALAMAN PENILAIAN ===== */
  .card{
    background:#ffffff;
    border-radius:16px;
    border:1px solid rgba(13,23,84,.10);
    box-shadow:0 6px 20px rgba(13,23,84,.08);
  }
  .card-bd{ padding:16px 18px; }
  .card-ft{
    padding:12px 18px;
    border-top:1px solid #eef1f6;
    background:#fcfdff;
  }

  .toolbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:8px;
    flex-wrap:wrap;
  }
  .filters{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
  }
  .filters label{
    font-size:14px;
    color:#0b1d54;
    font-weight:700;
  }
  .filters select,
  .filters input{
    padding:8px 12px;
    border:1px solid #d8dfeb;
    border-radius:8px;
    background:#fff;
    font-size:14px;
  }

  .btn{
    border:0;
    padding:8px 16px;
    border-radius:8px;
    font-size:14px;
    font-weight:700;
    cursor:pointer;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:6px;
  }
  .btn-primary{ background:#0e257a; color:#fff; }
  .btn-primary:hover{ background:#0b1d54; }
  .btn-secondary{ background:#eef3fa; color:#0e257a; }
  .btn-secondary:hover{ background:#e3eaf5; }
  .btn-success{ background:#00b167; color:#fff; }
  .btn-success:hover{ background:#009354; }
  .btn-warning{ background:#ff8c00; color:#fff; }
  .btn-warning:hover{ background:#e07b00; }

  .table-wrap{ overflow:auto; }
  table{ width:100%; border-collapse:collapse; min-width:1000px; }
  th,td{
    padding:10px 12px;
    font-size:14px;
    border-bottom:1px solid #eef1f6;
    vertical-align:middle;
  }
  thead th{
    background:#eef3fa;
    color:#0b1d54;
    text-align:left;
    font-size:12px;
    text-transform:uppercase;
  }
  tbody tr:hover td{ background:#f9fbff; }

  .grade-cell{ position:relative; }
  .grade-input{
    width:60px;
    padding:6px 8px;
    border:1px solid #d8dfeb;
    border-radius:6px;
    text-align:center;
    font-size:14px;
    transition:border .2s;
  }
  .grade-input:focus{
    border-color:#2f73ff;
    outline:2px solid #2f73ff;
    outline-offset:-1px;
  }
  .grade-input.dirty{ border-color:#ff8c00; }

  .btn-delete-grade{
    position:absolute;
    top:50%;
    right:14px;
    transform:translateY(-50%);
    border:0;
    background:transparent;
    color:#aaa;
    font-size:18px;
    cursor:pointer;
    display:none;
  }
  .grade-cell:hover .btn-delete-grade{ display:block; }

  .final-grade{
    font-weight:700;
    color:#0e257a;
  }
  .weight-total.error{ color:#e53935; font-weight:700; }

  @media (max-width:980px){
    .toolbar{
      flex-direction:column;
      align-items:stretch;
      gap:16px;
    }
  }
</style>

<div class="toolbar">
  <form id="filter-form" method="GET" action="{{ route('dosenpenguji.penilaian') }}" class="filters">
    <label for="filter-mk">Mata Kuliah:</label>
    <select id="filter-mk" name="matakuliah" onchange="this.form.submit()">
      <option value="">Pilih MK</option>
      @isset($matakuliah)
        @foreach($matakuliah as $mk)
          <option value="{{ $mk->kode_mk }}" @selected(request('matakuliah') == $mk->kode_mk)>
            {{ $mk->nama_mk }}
          </option>
        @endforeach
      @endisset
    </select>

    <label for="filter-kelas">Kelas:</label>
    <select id="filter-kelas" name="kelas" onchange="this.form.submit()">
      <option value="">Semua Kelas</option>
      @foreach (['A','B','C','D','E'] as $kelas)
        <option value="{{ $kelas }}" @selected(request('kelas') == $kelas)>Kelas {{ $kelas }}</option>
      @endforeach
    </select>
  </form>

  {{-- ====== BLOK ACTIONS (Import/Export/Tambah/Simpan) ====== --}}
  <div style="display:flex; gap:10px; flex-wrap:wrap;">
    {{-- Export Excel --}}
    <a class="btn btn-secondary"
       href="{{ route('dosenpenguji.penilaian.export.excel', request()->only('matakuliah','kelas')) }}">
      <i class="fa-solid fa-file-excel"></i> Export Excel
    </a>

    {{-- Export PDF --}}
    <a class="btn btn-secondary"
       href="{{ route('dosenpenguji.penilaian.export.pdf', request()->only('matakuliah','kelas')) }}">
      <i class="fa-solid fa-file-pdf"></i> Export PDF
    </a>

    {{-- Import Excel --}}
    <form id="importForm"
          action="{{ route('dosenpenguji.penilaian.import', request()->only('matakuliah','kelas')) }}"
          method="POST" enctype="multipart/form-data">
      @csrf
      <input type="file" name="file" id="importFile"
             accept=".xlsx,.xls"
             style="display:none"
             onchange="document.getElementById('importForm').submit()">
      <button type="button" class="btn btn-secondary"
              onclick="document.getElementById('importFile').click()">
        <i class="fa-solid fa-upload"></i> Import Excel
      </button>
    </form>

    {{-- Tambah Nilai --}}
    <a href="{{ route('dosenpenguji.penilaian.item.create', request()->query()) }}" class="btn btn-warning">
      <i class="fa-solid fa-plus"></i> Tambah Nilai
    </a>

    {{-- Simpan Semua --}}
    <button type="submit" form="grade-form" class="btn btn-success">
      <i class="fa-solid fa-save"></i> Simpan Semua
    </button>
  </div>
  {{-- ====== /BLOK ACTIONS ====== --}}
</div>

<form id="grade-form" action="{{ route('dosenpenguji.penilaian.bulkSave') }}" method="POST">
  @csrf
  <input type="hidden" name="matakuliah" value="{{ request('matakuliah') }}">
  <input type="hidden" name="kelas" value="{{ request('kelas') }}">

  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:180px">Mahasiswa</th>
            @php $totalBobot = 0; @endphp
            @forelse (($rubrics ?? collect()) as $rubric)
              @php $totalBobot += $rubric->bobot; @endphp
              <th class="text-center">
                {{ $rubric->nama_rubrik }}
                (
                  <input type="number"
                         name="bobot[{{ $rubric->id }}]"
                         value="{{ $rubric->bobot }}"
                         class="weight-input"
                         style="width:50px;"
                         data-id="{{ $rubric->id }}">%
                )
              </th>
            @empty
              <th>Komponen Penilaian Belum Ada</th>
            @endforelse
            <th style="width:100px">Nilai Akhir</th>
          </tr>
        </thead>
        <tbody>
          @forelse (($mahasiswa ?? collect()) as $m)
            <tr class="student-row" data-nim="{{ $m->nim }}">
              <td>
                <strong>{{ $m->nama }}</strong><br>
                <small>{{ $m->nim }}</small>
              </td>

              @forelse (($rubrics ?? collect()) as $rubric)
                <td>
                  <div class="grade-cell">
                    <input type="number" class="grade-input"
                           name="nilai[{{ $m->nim }}][{{ $rubric->id }}]"
                           value="{{ $m->penilaian->firstWhere('rubric_id', $rubric->id)->nilai ?? '' }}"
                           min="0" max="100"
                           data-nim="{{ $m->nim }}" data-rubric-id="{{ $rubric->id }}">
                    <button type="button" class="btn-delete-grade">&times;</button>
                  </div>
                </td>
              @empty
                <td>-</td>
              @endforelse

              <td class="final-grade" id="final-grade-{{ $m->nim }}">0.00</td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ count($rubrics ?? []) + 2 }}"
                  style="text-align:center; padding: 20px;">
                Pilih Mata Kuliah untuk menampilkan mahasiswa dan komponen penilaian.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-ft">
      <div class="toolbar">
        <div id="total-bobot-container">
          Total Bobot: <strong id="total-bobot">{{ $totalBobot }}</strong>%
        </div>
        @if (isset($mahasiswa) && method_exists($mahasiswa, 'hasPages') && $mahasiswa->hasPages())
          {{ $mahasiswa->links() }}
        @endif
      </div>
    </div>
  </div>
</form>

{{-- ===== JS KHUSUS PERHITUNGAN NILAI ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const gradeForm = document.getElementById('grade-form');
  if (!gradeForm) return;

  const csrfToken = '{{ csrf_token() }}';

  // init: hitung nilai awal & tombol hapus
  document.querySelectorAll('.student-row').forEach(row => {
    calculateFinalGrade(row);
    row.querySelectorAll('.grade-input').forEach(input => {
      updateDeleteButtonVisibility(input);
    });
  });
  updateTotalBobot();

  gradeForm.addEventListener('input', function (e) {
    if (e.target.classList.contains('grade-input')) {
      const input = e.target;
      input.classList.add('dirty');
      updateDeleteButtonVisibility(input);
      calculateFinalGrade(input.closest('.student-row'));
    }
    if (e.target.classList.contains('weight-input')) {
      updateTotalBobot();
      document.querySelectorAll('.student-row')
              .forEach(row => calculateFinalGrade(row));
    }
  });

  gradeForm.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-delete-grade')) {
      handleDeleteGrade(e.target);
    }
  });

  function handleDeleteGrade(button) {
    const input = button.previousElementSibling;
    const nim = input.dataset.nim;
    const rubricId = input.dataset.rubricId;

    if (!confirm('Anda yakin ingin menghapus nilai ini? Aksi ini tidak dapat dibatalkan.')) return;

    const url = `/dosenpenguji/penilaian/grade/${nim}/${rubricId}`;

    fetch(url, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      }
    })
    .then(response => {
      if (!response.ok) {
        return response.json().then(err => {
          throw new Error(err.message || 'Gagal menghubungi server.');
        });
      }
      return response.json();
    })
    .then(() => {
      input.value = '';
      input.classList.remove('dirty');
      updateDeleteButtonVisibility(input);
      calculateFinalGrade(input.closest('.student-row'));
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan: ' + error.message);
    });
  }

  function updateDeleteButtonVisibility(input) {
    const deleteBtn = input.nextElementSibling;
    if (deleteBtn) {
      deleteBtn.style.display = input.value.trim() !== '' ? 'block' : 'none';
    }
  }

  function calculateFinalGrade(studentRow) {
    let totalNilaiBerbobot = 0;
    let totalBobot = 0;
    const gradeInputs = studentRow.querySelectorAll('.grade-input');

    gradeInputs.forEach(input => {
      const rubricId = input.dataset.rubricId;
      const weightInput = document.querySelector(`.weight-input[data-id='${rubricId}']`);
      const bobot = parseFloat(weightInput?.value) || 0;
      const nilai = parseFloat(input.value) || 0;

      if (input.value !== '') {
        totalNilaiBerbobot += (nilai * bobot);
        totalBobot += bobot;
      }
    });

    const finalGrade = totalBobot > 0 ? (totalNilaiBerbobot / totalBobot) : 0;
    const finalGradeCell = studentRow.querySelector('.final-grade');
    finalGradeCell.textContent = finalGrade.toFixed(2);
  }

  function updateTotalBobot() {
    let total = 0;
    document.querySelectorAll('.weight-input').forEach(input => {
      total += parseFloat(input.value) || 0;
    });
    const totalBobotEl = document.getElementById('total-bobot');
    totalBobotEl.textContent = total;
    const container = document.getElementById('total-bobot-container');
    if (total !== 100) {
      container.classList.add('error');
    } else {
      container.classList.remove('error');
    }
  }
});

// Tutup sidebar ketika klik di luar (mobile)
document.addEventListener('click', (e) => {
  const sb = document.getElementById('sidebar');
  if(!sb || !sb.classList.contains('show')) return;
  const btn = e.target.closest('.topbar-btn');
  if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
});
</script>
@endsection
