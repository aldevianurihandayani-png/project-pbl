@extends('layouts.koordinator')

@section('title', 'Atur Bobot Peringkat')

@section('content')

<style>
    .wrap{ max-width:980px; margin:22px auto; padding:0 14px; }
    .cardx{
        background:#fff; border-radius:18px; border:1px solid #e8ecfb;
        box-shadow:0 14px 30px rgba(0,0,0,.10); overflow:hidden; margin-bottom:16px;
    }
    .cardx-hd{
        display:flex; align-items:center; gap:12px;
        padding:16px 18px; border-bottom:1px solid #eef1fb;
    }
    .badge{
        width:36px; height:36px; border-radius:12px;
        display:flex; align-items:center; justify-content:center;
        background:#eef2ff; color:#0e257a;
    }
    .cardx-hd h3{ margin:0; font-size:18px; font-weight:900; color:#0e257a; flex:1; }
    .cardx-bd{ padding:18px; }

    .grid2{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    @media(max-width:900px){ .grid2{ grid-template-columns:1fr; } }

    .row{
        display:grid; grid-template-columns:220px 1fr 120px;
        gap:12px; align-items:center; padding:10px 0; border-bottom:1px solid #f2f4ff;
    }
    @media(max-width:700px){
        .row{ grid-template-columns:1fr; }
    }

    .label{ font-weight:800; color:#1a2440; }
    .hint{ color:#667085; font-size:12px; margin-top:6px; line-height:1.35; }

    input[type="range"]{ width:100%; }
    .num{
        width:100%; padding:10px 12px; border-radius:12px;
        border:1px solid #dfe6fb; outline:none;
    }
    .num:focus{ border-color:#9fb3ff; box-shadow:0 0 0 4px rgba(80,110,255,.12); }

    .sumbar{
        display:flex; gap:10px; align-items:center; justify-content:space-between;
        padding:12px 14px; border-radius:14px; background:#f6f8ff; border:1px solid #e1e6f8;
        margin-top:12px;
    }
    .sumok{ background:#e6ffed; border-color:#b7f2c6; color:#13653f; }
    .sumwarn{ background:#fff7e6; border-color:#ffe0a3; color:#7a4b00; }
    .sumerr{ background:#ffecec; border-color:#ffd2d2; color:#8a1f1f; }
    .sumtxt{ font-weight:900; }
    .sumval{ font-weight:900; font-size:18px; }

    .actions{
        display:flex; gap:10px; flex-wrap:wrap;
        padding:14px 18px; border-top:1px solid #eef1fb; background:#fff;
    }
    .btnx{
        display:inline-flex; align-items:center; gap:10px;
        padding:10px 16px; border-radius:14px; font-weight:900;
        border:0; cursor:pointer; text-decoration:none !important;
        transition:.12s;
    }
    .btnx:hover{ filter:brightness(1.03); transform:translateY(-1px); }
    .btnx-primary{ background:#0e257a; color:#fff; }
    .btnx-ghost{ background:#eef2ff; color:#0e257a; border:1px solid #d7def7; }
    .btnx-danger{ background:#b42318; color:#fff; }

    .alertx{
        padding:10px 12px; border-radius:12px; margin-bottom:12px; font-weight:800;
    }
    .alertx-success{ background:#e6ffed; color:#13653f; border:1px solid #b7f2c6; }
    .alertx-danger{ background:#ffecec; color:#8a1f1f; border:1px solid #ffd2d2; }
</style>

<div class="wrap">

    @if(session('success'))
        <div class="alertx alertx-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alertx alertx-danger">
            <div style="margin-bottom:6px;">Ada input yang belum valid:</div>
            <ul style="margin:0 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('koordinator.peringkat.bobot.store') }}">
        @csrf

        <div class="grid2">

            {{-- ================== BOBOT MAHASISWA ================== --}}
            <div class="cardx" id="card-mahasiswa">
                <div class="cardx-hd">
                    <div class="badge"><i class="fa-solid fa-user-graduate"></i></div>
                    <h3>Bobot Mahasiswa</h3>
                </div>

                <div class="cardx-bd">
                    <div class="row">
                        <div>
                            <div class="label">Keaktifan</div>
                            <div class="hint">0–100% (total boleh ≤ 100%, nanti dinormalisasi saat hitung SAW).</div>
                        </div>
                        <input type="range" min="0" max="100" step="1" class="rng mhs" id="rng_keaktifan"
                               value="{{ old('mhs_keaktifan', $mhs->mhs_keaktifan ?? 30) }}">
                        <input type="number" min="0" max="100" step="1" class="num mhs" name="mhs_keaktifan" id="num_keaktifan"
                               value="{{ old('mhs_keaktifan', $mhs->mhs_keaktifan ?? 30) }}">
                    </div>

                    <div class="row">
                        <div>
                            <div class="label">Nilai Kelompok</div>
                            <div class="hint">Kontribusi nilai kelompok terhadap peringkat mahasiswa.</div>
                        </div>
                        <input type="range" min="0" max="100" step="1" class="rng mhs" id="rng_nilai_kelompok"
                               value="{{ old('mhs_nilai_kelompok', $mhs->mhs_nilai_kelompok ?? 30) }}">
                        <input type="number" min="0" max="100" step="1" class="num mhs" name="mhs_nilai_kelompok" id="num_nilai_kelompok"
                               value="{{ old('mhs_nilai_kelompok', $mhs->mhs_nilai_kelompok ?? 30) }}">
                    </div>

                    <div class="row" style="border-bottom:0;">
                        <div>
                            <div class="label">Nilai Dosen</div>
                            <div class="hint">Penilaian dosen terhadap mahasiswa.</div>
                        </div>
                        <input type="range" min="0" max="100" step="1" class="rng mhs" id="rng_nilai_dosen"
                               value="{{ old('mhs_nilai_dosen', $mhs->mhs_nilai_dosen ?? 40) }}">
                        <input type="number" min="0" max="100" step="1" class="num mhs" name="mhs_nilai_dosen" id="num_nilai_dosen"
                               value="{{ old('mhs_nilai_dosen', $mhs->mhs_nilai_dosen ?? 40) }}">
                    </div>

                    <div class="sumbar" id="sumbar-mhs">
                        <div class="sumtxt">Total Mahasiswa</div>
                        <div class="sumval" id="sum-mhs">0%</div>
                    </div>
                </div>
            </div>

            {{-- ================== BOBOT KELOMPOK ================== --}}
            <div class="cardx" id="card-kelompok">
                <div class="cardx-hd">
                    <div class="badge"><i class="fa-solid fa-users"></i></div>
                    <h3>Bobot Kelompok</h3>
                </div>

                <div class="cardx-bd">
                    <div class="row">
                        <div>
                            <div class="label">Review UTS</div>
                            <div class="hint">0–100% (total boleh ≤ 100%, nanti dinormalisasi saat hitung SAW).</div>
                        </div>
                        <input type="range" min="0" max="100" step="1" class="rng klp" id="rng_review_uts"
                               value="{{ old('klp_review_uts', $klp->klp_review_uts ?? 50) }}">
                        <input type="number" min="0" max="100" step="1" class="num klp" name="klp_review_uts" id="num_review_uts"
                               value="{{ old('klp_review_uts', $klp->klp_review_uts ?? 50) }}">
                    </div>

                    <div class="row" style="border-bottom:0;">
                        <div>
                            <div class="label">Review UAS</div>
                            <div class="hint">Nilai akhir presentasi / laporan akhir.</div>
                        </div>
                        <input type="range" min="0" max="100" step="1" class="rng klp" id="rng_review_uas"
                               value="{{ old('klp_review_uas', $klp->klp_review_uas ?? 50) }}">
                        <input type="number" min="0" max="100" step="1" class="num klp" name="klp_review_uas" id="num_review_uas"
                               value="{{ old('klp_review_uas', $klp->klp_review_uas ?? 50) }}">
                    </div>

                    <div class="sumbar" id="sumbar-klp">
                        <div class="sumtxt">Total Kelompok</div>
                        <div class="sumval" id="sum-klp">0%</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="actions">
            <button type="submit" class="btnx btnx-primary" id="btn-save">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Bobot
            </button>

            <a href="{{ route('koordinator.peringkat.index') }}" class="btnx btnx-ghost">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>

            <button type="button" class="btnx btnx-danger" id="btn-reset">
                <i class="fa-solid fa-rotate-left"></i> Reset Default
            </button>
        </div>

    </form>
</div>

<script>
(function(){
    function bindPair(rangeId, numId){
        const r = document.getElementById(rangeId);
        const n = document.getElementById(numId);
        if(!r || !n) return;

        r.addEventListener('input', ()=>{ n.value = r.value; updateSums(); });
        n.addEventListener('input', ()=>{
            let v = parseInt(n.value || 0);
            if(v < 0) v = 0;
            if(v > 100) v = 100;
            n.value = v;
            r.value = v;
            updateSums();
        });
    }

    bindPair('rng_keaktifan','num_keaktifan');
    bindPair('rng_nilai_kelompok','num_nilai_kelompok');
    bindPair('rng_nilai_dosen','num_nilai_dosen');
    bindPair('rng_review_uts','num_review_uts');
    bindPair('rng_review_uas','num_review_uas');

    function sumOf(className){
        let sum = 0;
        document.querySelectorAll('input.num.'+className).forEach(el=>{
            sum += parseInt(el.value || 0);
        });
        return sum;
    }

    function paint(sum, sumEl, barEl){
        sumEl.textContent = sum + '%';
        barEl.classList.remove('sumok','sumwarn','sumerr');

        // aturan: boleh <= 100
        if(sum > 100) barEl.classList.add('sumerr');
        else if(sum === 100) barEl.classList.add('sumok');
        else barEl.classList.add('sumwarn');
    }

    function updateSums(){
        const m = sumOf('mhs');
        const k = sumOf('klp');

        paint(m, document.getElementById('sum-mhs'), document.getElementById('sumbar-mhs'));
        paint(k, document.getElementById('sum-klp'), document.getElementById('sumbar-klp'));

        // disable save hanya kalau ada yg > 100
        const btn = document.getElementById('btn-save');
        const ok = (m <= 100 && k <= 100);
        if(btn) btn.disabled = !ok;
        if(btn) btn.style.opacity = ok ? '1' : '.65';
        if(btn) btn.style.cursor = ok ? 'pointer' : 'not-allowed';
    }

    document.getElementById('btn-reset')?.addEventListener('click', ()=>{
        const defaults = {
            mhs_keaktifan: 30,
            mhs_nilai_kelompok: 30,
            mhs_nilai_dosen: 40,
            klp_review_uts: 50,
            klp_review_uas: 50,
        };

        document.getElementById('rng_keaktifan').value = defaults.mhs_keaktifan;
        document.getElementById('num_keaktifan').value = defaults.mhs_keaktifan;

        document.getElementById('rng_nilai_kelompok').value = defaults.mhs_nilai_kelompok;
        document.getElementById('num_nilai_kelompok').value = defaults.mhs_nilai_kelompok;

        document.getElementById('rng_nilai_dosen').value = defaults.mhs_nilai_dosen;
        document.getElementById('num_nilai_dosen').value = defaults.mhs_nilai_dosen;

        document.getElementById('rng_review_uts').value = defaults.klp_review_uts;
        document.getElementById('num_review_uts').value = defaults.klp_review_uts;

        document.getElementById('rng_review_uas').value = defaults.klp_review_uas;
        document.getElementById('num_review_uas').value = defaults.klp_review_uas;

        updateSums();
    });

    updateSums();
})();
</script>

@endsection
