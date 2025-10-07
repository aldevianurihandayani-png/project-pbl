@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h1 class="mb-3">Edit Matakuliah</h1>
  <form action="{{ route('matakuliah.update', $matakuliah) }}" method="POST">
    @method('PUT')
    @include('matakuliah._form', ['submit' => 'Update'])
  </form>
</div>
@endsection
