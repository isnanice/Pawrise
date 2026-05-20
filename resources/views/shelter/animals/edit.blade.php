@extends('layouts.shelter')
@section('title', 'Edit Hewan - PawRise Shelter')
@section('content')

<div class="mb-4">
    <h3 class="fw-bold mb-1">Edit Data Hewan</h3>
    <p class="text-muted" style="font-size:.9rem;">Perbarui informasi hewan <strong>{{ $animal->name }}</strong> pada shelter Anda</p>
</div>

@if($errors->any())
    <div class="alert alert-danger rounded-3 mb-4">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('shelter.animals.update', $animal) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('shelter.animals._form', ['animal' => $animal])
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('shelter.animals.index') }}" class="btn btn-outline-secondary px-4 rounded-3">Batal</a>
        <button type="submit" class="btn pr-btn-primary px-5">Perbarui</button>
    </div>
</form>

@endsection