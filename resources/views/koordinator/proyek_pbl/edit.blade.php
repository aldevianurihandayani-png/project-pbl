@extends('layouts.koordinator')

@section('content')
<div class="container">
  <h4 class="mb-3">Edit Proyek PBL</h4>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('koordinator.proyek-pbl.update', $item->id_proyek_pbl) }}">
        @csrf
        @method('PUT')

        @include('koordinator.proyek_pbl._form', ['item' => $item])
      </form>
    </div>
  </div>
</div>
@endsection
