@extends('layouts.layout')

@section('content')
<section class="page-section" id="add-file">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Add File to Group</h2>
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="fileUploadForm" action="{{ route('storeFile', ['groupId' => $group->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file" class="font-weight-bold">Select File</label>
                <input type="file" name="file" id="file" class="form-control-file border rounded p-2" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg mt-3">
                    <i class="fas fa-upload"></i> Upload File
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    .page-section {
        padding: 60px 0;
    }
    .divider-custom {
        margin: 1.5rem auto 3rem;
        width: 100%;
        max-width: 7.5rem;
        text-align: center;
    }
    .divider-custom-line {
        height: 0.25rem;
        background-color: #2c3e50;
        border-radius: 1rem;
    }
    .divider-custom-icon {
        font-size: 2rem;
        line-height: 1;
        color: #2c3e50;
    }
    .form-control-file {
        padding: 10px;
        border: 2px solid #2c3e50;
    }
    .btn-primary {
        background-color: #2c3e50;
        border-color: #2c3e50;
    }
    .btn-primary:hover {
        background-color: #1a242f;
        border-color: #1a242f;
    }
    .alert-danger {
        margin-top: 20px;
    }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.getElementById('fileUploadForm').addEventListener('submit', function(event) {
        var fileInput = document.getElementById('file');
        var filePath = fileInput.value;
        var allowedExtensions = /(\.pdf|\.csv)$/i;

        if (!allowedExtensions.exec(filePath)) {
            alert('Please upload file having extensions .pdf/.csv only.');
            fileInput.value = '';
            event.preventDefault();
        }
    });
</script>
@endsection
