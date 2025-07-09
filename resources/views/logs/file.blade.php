@extends('layouts.layout')

@section('content')
<section class="page-section" id="file-logs">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">File Logs </h2>
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-file-alt"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        <div class="row">
            @forelse($file->logs as $log)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $log->action }}</h5>
                            <p class="card-text">
                                <strong>Performed By:</strong> {{ $log->user->name }}<br>
                                <strong>Details:</strong> {{ $log->details }}<br>
                                <strong>Date:</strong> {{ $log->created_at->format('d M, Y - H:i') }}
                            </p>
                            <div class="text-right">
                                <!-- Export Button with Dropdown -->
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('export.log', ['logId' => $log->id, 'format' => 'csv']) }}">Export as CSV</a>
                                        <a class="dropdown-item" href="{{ route('export.log', ['logId' => $log->id, 'format' => 'pdf']) }}">Export as PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">No logs found for this file.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<!-- Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection

<style>
.card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.3);
}

.card-body {
    background-color: #f8f9fa;
    padding: 1.5rem;
}

.card-title {
    font-size: 1.25rem;
    font-weight: bold;
}

.card-text {
    font-size: 1rem;
    line-height: 1.5;
}

.divider-custom {
    margin-bottom: 2rem;
}

.divider-custom-line {
    height: 1px;
    background-color: #6c757d;
    flex-grow: 1;
}

.divider-custom-icon {
    font-size: 2rem;
    color: #6c757d;
}

.card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}
</style>
