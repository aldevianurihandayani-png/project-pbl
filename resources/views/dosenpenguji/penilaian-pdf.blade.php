<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Penilaian PDF</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 5px; vertical-align: top; }
    th { background: #f2f2f2; font-weight: 700; }
    .small { font-size: 10px; color: #333; }
    .center { text-align: center; }
    .right { text-align: right; }
  </style>
</head>
<body>

  <h3 style="margin:0 0 6px 0;">Rekap Penilaian</h3>

  <p style="margin:0 0 10px 0;">
    Mata Kuliah:
    <b>
      @if(!empty($mkNama))
        {{ $mkNama }} ({{ $kodeMk ?? '-' }})
      @else
        {{ $kodeMk ?? '-' }}
      @endif
    </b>
    <br>
    Kelas: <b>{{ $kelas ?? '-' }}</b>
  </p>

  @php
    $rubCount = isset($rubrics) ? $rubrics->count() : 0;
    $colspan  = 1 + ($rubCount > 0 ? $rubCount : 1) + 1;
  @endphp

  <table>
    <thead>
      <tr>
        <th style="width:170px;">Mahasiswa</th>

        @forelse($rubrics as $r)
          <th class="center">
            {{ $r->nama_rubrik }}
            <div class="small">({{ (int)$r->bobot }}%)</div>
          </th>
        @empty
          <th>Komponen belum ada</th>
        @endforelse

        <th class="center" style="width:90px;">Nilai Akhir</th>
      </tr>
    </thead>

    <tbody>
      @if(empty($kodeMk))
        <tr>
          <td colspan="{{ $colspan }}" class="center">Pilih Mata Kuliah</td>
        </tr>

      @elseif(($mahasiswa?->count() ?? 0) === 0)
        <tr>
          <td colspan="{{ $colspan }}" class="center">Mahasiswa tidak ditemukan</td>
        </tr>

      @else
        @foreach($mahasiswa as $m)
          @php
            // hitung nilai akhir = Σ(nilai * bobot/100)
            $final = 0;
            foreach(($rubrics ?? collect()) as $r) {
              $val = optional($m->penilaian->firstWhere('rubrik_id', $r->id))->nilai;
              if ($val === '' || $val === null) continue;
              if (!is_numeric($val)) continue;
              $final += ((float)$val) * (((float)$r->bobot) / 100.0);
            }
          @endphp

          <tr>
            <td>
              <b>{{ $m->nama ?? '-' }}</b><br>
              <span class="small">{{ $m->nim ?? '-' }} {{ $m->kelas ?? '' }}</span>
            </td>

            @forelse($rubrics as $r)
              @php
                $val = optional($m->penilaian->firstWhere('rubrik_id', $r->id))->nilai;
              @endphp
              <td class="center">{{ ($val === null || $val === '') ? '-' : $val }}</td>
            @empty
              <td class="center">-</td>
            @endforelse

            <td class="center"><b>{{ number_format($final, 2) }}</b></td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>

</body>
</html>
