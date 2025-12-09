@extends('layouts.koordinator')

@section('title', 'Tambah Peringkat')

@section('content')
<div class="page">
    <section class="card">
        <div class="card-hd">
            <i class="fa-solid fa-plus"></i> Tambah Peringkat
        </div>
        <div class="card-bd">
            @if($errors->any())
                <div style="padding:8px 12px;border-radius:8px;background:#ffebee;color:#b71c1c;margin-bottom:10px;">
                    <strong>Terjadi kesalahan:</strong>
                    <ul style="margin:4px 0 0 18px;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('koordinator.peringkat.store') }}" method="POST">
                @include('koordinator.peringkat._form', ['peringkat' => new \App\Models\Peringkat()])
            </form>
        </div>
    </section>
</div>
@endsection
