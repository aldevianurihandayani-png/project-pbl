@extends('dosenpenguji.layout')

@section('content')
<div class="container py-3" style="max-width: 820px;">
    <a href="{{ route('help.index') }}" class="text-decoration-none">&larr; Kembali</a>

    <div class="card border-0 shadow-sm mt-2">
        <div class="card-body" style="background:#e5ddd5; border-radius: 12px;">
            <div class="d-flex justify-content-end mb-2">
                <div class="p-2 px-3" style="max-width: 75%; background:#dcf8c6; border-radius:14px;">
                    {{ $item['q'] }}
                </div>
            </div>

            <div class="d-flex mb-2">
                <div class="p-2 px-3" style="max-width: 75%; background:#fff; border-radius:14px;">
                    <div class="fw-semibold mb-1">Admin</div>
                    <ul class="mb-0 ps-3">
                        @foreach($item['a'] as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
