{{-- @extends('layouts.layout')

@section('content')
<section class="page-section" id="edit-file">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Edit File</h2>

        <form action="{{ route('updateFile', ['fileId' => $file->id]) }}" method="POST">
            @csrf
            @method('POST') <!-- يمكن أن تكون PUT إذا كان التعديل يتطلب PUT -->
            <div class="form-group">
                <label for="name">File Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $file->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update File</button>
        </form>

    </div>
</section>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection --}}

@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <h1>Edit File: {{ $file->name }}</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('downloadFile', ['fileId' => $file->id]) }}" class="btn btn-primary mb-3">
        <i class="fas fa-download"></i> Download Current File
    </a>
    <form action="{{ route('updateFile', ['fileId' => $file->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mt-4">
            <label for="updated_file">Upload Updated File:</label>
            <input type="file" name="updated_file" id="updated_file" class="form-control" required>
        </div>
        @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
    </form>
</div>
@endsection
