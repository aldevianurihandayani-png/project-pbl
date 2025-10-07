@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h1 class="mb-3">Tambah Matakuliah</h1>
  <form action="{{ route('matakuliah.store') }}" method="POST">
    @include('matakuliah._form', ['submit' => 'Tambah'])
  </form>
</div>
@endsection
