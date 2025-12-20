@extends('layouts.mahasiswa')

@section('title', 'Laporan Penilaian — Mahasiswa')
@section('page_title', 'Laporan Penilaian')

@section('content')
@php
  // endpoint data (yang kamu sudah pasang di routes)
  $dataUrl = route('mahasiswa.laporan_penilaian.index');
@endphp

<style>
  .toolbar{display:flex;gap:12px;align-items:center;justify-content:space-between;flex-wrap:wrap;margin-bottom:14px}
  .filters{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
  .filters label{font-size:14px;color:#0b1d54;font-weight:700}
  .filters select,.filters input{
    padding:8px 12px;border:1px solid #d8dfeb;border-radius:8px;background:#fff;font-size:14px
  }
  .btn{
    display:inline-flex;gap:6px;align-items:center;
    padding:8px 12px;border-radius:8px;background:#eef3fa;color:#0e257a;
    text-decoration:none;font-weight:800;border:0;cursor:pointer
  }
  .btn:hover{background:#e3eaf5}
  .muted{color:#6c7a8a;font-size:12px}
  .table-wrap{overflow:auto}
  table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;min-width:980px}
  th,td{padding:10px 12px;border-bottom:1px solid #eef1f6;text-align:center;vertical-align:middle;white-space:nowrap}
  th{background:#eef3fa;font-size:12px;text-transform:uppercase;color:#0b1d54}
  td.left, th.left{text-align:left;white-space:normal;min-width:260px}
  .pill{
    display:inline-flex;gap:8px;align-items:center;
    padding:8px 12px;border-radius:999px;background:#eef3fa;color:#0e257a;
    font-weight:800;font-size:12px
  }
  .note{margin-top:10px}
  .skeleton{padding:14px;border:1px dashed #d8dfeb;border-radius:12px;background:#fbfdff}
</style>

<section class="card">
  <div class="card-hd">
    <i class="fa-solid fa-file-lines"></i> Laporan Penilaian
  </div>

  <div class="card-bd">

    {{-- FILTER --}}
    <div class="toolbar">
      <div>
        <div style="font-weight:900;color:#0e257a">Rekap Nilai</div>
      </div>

      <form class="filters" id="filterForm">
        <label for="mk">Mata Kuliah:</label>
        <select id="mk" name="matakuliah">
          <option value="">Semua MK</option>
        </select>

        <label for="kelas">Kelas:</label>
        <select id="kelas" name="kelas">
          <option value="">Semua Kelas</option>
        </select>

        <button type="submit" class="btn">
          <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>

        <button type="button" class="btn" id="btnReset" style="display:none">
          <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
      </form>
    </div>

    {{-- INFO --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px">
      <span class="pill"><i class="fa-solid fa-user"></i> <span id="infoNim">NIM: -</span></span>
      <span class="pill"><i class="fa-solid fa-book"></i> <span id="infoMk">MK: -</span></span>
      <span class="pill"><i class="fa-solid fa-people-group"></i> <span id="infoKelas">Kelas: -</span></span>
    </div>

    {{-- TABLE --}}
    <div id="emptyBox" class="skeleton" style="display:none">
      <div class="muted">Belum ada data penilaian.</div>
    </div>

    <div class="table-wrap" id="tableWrap" style="display:none">
      <table id="nilaiTable">
        <thead id="thead"></thead>
        <tbody id="tbody"></tbody>
        <tfoot id="tfoot"></tfoot>
      </table>
    </div>

  </div>
</section>

<script>
  const DATA_URL = @json($dataUrl);

  const elMk       = document.getElementById('mk');
  const elKelas    = document.getElementById('kelas');
  const elForm     = document.getElementById('filterForm');
  const elReset    = document.getElementById('btnReset');

  const infoNim    = document.getElementById('infoNim');
  const infoMk     = document.getElementById('infoMk');
  const infoKelas  = document.getElementById('infoKelas');

  const emptyBox   = document.getElementById('emptyBox');
  const tableWrap  = document.getElementById('tableWrap');

  const thead      = document.getElementById('thead');
  const tbody      = document.getElementById('tbody');
  const tfoot      = document.getElementById('tfoot');

  function qs(name){
    return new URLSearchParams(window.location.search).get(name) || '';
  }

  function setQuery(params){
    const sp = new URLSearchParams();
    Object.entries(params).forEach(([k,v]) => {
      if (v !== null && v !== undefined && String(v).trim() !== '') sp.set(k, v);
    });
    const url = `${window.location.pathname}${sp.toString() ? ('?' + sp.toString()) : ''}`;
    window.history.replaceState({}, '', url);
  }

  function fmtNumber(n){
    const x = Number(n);
    if (Number.isNaN(x)) return '-';
    return x.toFixed(2);
  }

  function safeText(v, fallback='-'){
    if (v === null || v === undefined || v === '') return fallback;
    return String(v);
  }

  function buildOptions(selectEl, items, valueKey, labelKey, selectedValue){
    const first = selectEl.querySelector('option[value=""]');
    selectEl.innerHTML = '';
    if (first) selectEl.appendChild(first);

    (items || []).forEach(it => {
      const opt = document.createElement('option');
      opt.value = it[valueKey];
      opt.textContent = it[labelKey];
      if (String(selectedValue) === String(opt.value)) opt.selected = true;
      selectEl.appendChild(opt);
    });
  }

  function pickRowScore(row){
    return row?.skor ?? row?.nilai ?? row?.score ?? row?.value ?? null;
  }

  function pickRowRubrikId(row){
    return row?.rubrik_id ?? row?.rubric_id ?? row?.id_rubrik ?? null;
  }

  function pickNilaiAkhir(rows){
    if (!rows || !rows.length) return null;
    const first = rows[0];
    return first?.nilai_akhir ?? first?.nilaiAkhir ?? null;
  }

  function renderTable(payload){
    const rubrik = payload?.rubrik || [];
    const rows   = payload?.rows || [];

    // === DATA KOSONG (bedakan dari loading) ===
    if (!rows.length) {
      tableWrap.style.display = 'none';
      emptyBox.style.display = 'block';
      emptyBox.innerHTML = `<div class="muted">Belum ada data penilaian.</div>`;
      return;
    }

    emptyBox.style.display = 'none';
    tableWrap.style.display = 'block';

    // map nilai per rubrik_id
    const nilaiMap = new Map();
    rows.forEach(r => {
      const rid = pickRowRubrikId(r);
      if (!rid) return;
      nilaiMap.set(String(rid), r);
    });

    // header
    let headHtml = '<tr>';
    headHtml += '<th class="left">Mahasiswa</th>';

    rubrik.forEach(r => {
      const nama = safeText(r.nama_rubrik, 'Rubrik');
      const bobot = safeText(r.bobot, '');
      headHtml += `<th title="${nama}">${nama}${bobot !== '' ? ` (${bobot}%)` : ''}</th>`;
    });

    headHtml += '<th>Nilai Akhir</th>';
    headHtml += '</tr>';
    thead.innerHTML = headHtml;

    // body: 1 baris (mahasiswa login)
    const mhs  = rows[0]?.mahasiswa || null;
    const nim  = mhs?.nim ?? rows[0]?.nim ?? rows[0]?.mahasiswa_nim ?? rows[0]?.nim_mahasiswa ?? payload?.nim ?? null;
    const nama = mhs?.nama ?? mhs?.name ?? rows[0]?.nama ?? rows[0]?.nama_mahasiswa ?? 'Mahasiswa';

    let bodyHtml = '<tr>';
    bodyHtml += `<td class="left"><b>${safeText(nama)}</b><br><span class="muted">${safeText(nim)}</span></td>`;

    // hitung nilai akhir dari rubrik (kalau DB belum punya nilai_akhir)
    let total = 0;

    rubrik.forEach(r => {
      const rid = r.id;
      const row = nilaiMap.get(String(rid)) || null;
      const skor = pickRowScore(row);

      const bobot = Number(r.bobot ?? 0);
      const skorNum = Number(skor);

      if (!Number.isNaN(skorNum) && !Number.isNaN(bobot)) {
        total += (bobot / 100) * skorNum;
      }

      bodyHtml += `<td><b>${(skor === null || skor === undefined || skor === '') ? '-' : fmtNumber(skor)}</b></td>`;
    });

    // nilai akhir (prioritas ambil dari DB)
    let nilaiAkhir = pickNilaiAkhir(rows);
    if (nilaiAkhir === null || nilaiAkhir === undefined) {
      nilaiAkhir = total;
    }

    bodyHtml += `<td><b>${fmtNumber(nilaiAkhir)}</b></td>`;
    bodyHtml += '</tr>';

    tbody.innerHTML = bodyHtml;

    // footer
    tfoot.innerHTML = `
      <tr>
        <th class="left" colspan="${1 + rubrik.length}">Total Nilai Akhir</th>
        <th>${fmtNumber(nilaiAkhir)}</th>
      </tr>
    `;
  }

  function fillInfo(payload){
    infoNim.textContent = `NIM: ${safeText(payload?.nim)}`;

    const mkSelected = payload?.filters?.selected?.matakuliah || '';
    const kelasSelected = payload?.filters?.selected?.kelas || '';

    const mkList = payload?.filters?.matakuliah || [];
    const kelasList = payload?.filters?.kelas || [];

    const mkObj = mkList.find(x => String(x.kode_mk) === String(mkSelected));
    const kelasObj = kelasList.find(x => String(x.id) === String(kelasSelected));

    infoMk.textContent = `MK: ${mkObj ? mkObj.nama_mk : (mkSelected ? mkSelected : '-')}`;
    infoKelas.textContent = `Kelas: ${kelasObj ? (kelasObj.nama_kelas ?? ('Kelas #' + kelasObj.id)) : (kelasSelected ? kelasSelected : '-')}`;

    const hasFilter = !!mkSelected || !!kelasSelected;
    elReset.style.display = hasFilter ? 'inline-flex' : 'none';
  }

  async function loadData(){
    try {
      const params = {
        matakuliah: qs('matakuliah'),
        kelas: qs('kelas'),
      };

      // loading state
      emptyBox.style.display = 'block';
      emptyBox.innerHTML = `<div class="muted">Memuat data penilaian...</div>`;
      tableWrap.style.display = 'none';

      const url = new URL(DATA_URL, window.location.origin);
      if (params.matakuliah) url.searchParams.set('matakuliah', params.matakuliah);
      if (params.kelas) url.searchParams.set('kelas', params.kelas);

      const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('HTTP ' + res.status);

      const payload = await res.json();

      buildOptions(elMk, payload?.filters?.matakuliah || [], 'kode_mk', 'nama_mk', payload?.filters?.selected?.matakuliah || '');
      buildOptions(elKelas, payload?.filters?.kelas || [], 'id', 'nama_kelas', payload?.filters?.selected?.kelas || '');

      fillInfo(payload);
      renderTable(payload);

    } catch (err) {
      emptyBox.style.display = 'block';
      emptyBox.innerHTML = `<div class="muted">Gagal memuat data penilaian.</div>`;
      tableWrap.style.display = 'none';
      console.error(err);
    }
  }

  elForm.addEventListener('submit', function(e){
    e.preventDefault();
    const params = {
      matakuliah: elMk.value || '',
      kelas: elKelas.value || '',
    };
    setQuery(params);
    loadData();
  });

  elReset.addEventListener('click', function(){
    elMk.value = '';
    elKelas.value = '';
    setQuery({ matakuliah: '', kelas: '' });
    loadData();
  });

  loadData();
</script>
@endsection
