{@extends('layouts.layout')

@section('content')
<section class="page-section" id="memberLogs">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
            Member Logs for Group - {{ $group->name }}
        </h2>
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-file-alt"></i></div>
            <div class="divider-custom-line"></div>
        </div>

        <div class="row">
            @foreach ($logs as $log)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $log->user_name }}
                            </h5>
                            <p class="card-text"><strong>Action:</strong> {{ $log->action }}</p>
                            <p class="card-text"><strong>Details:</strong> {{ $log->details }}</p>
                            <p class="card-text"><strong>File:</strong> {{ $log->file_id ? 'File ' . $log->file_id : 'N/A' }}</p>
                            <p class="card-text"><strong>Created At:</strong> {{ $log->created_at }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($logs->isEmpty())
            <p class="text-center">No logs available for this group.</p>
        @endif
    </div>
</section>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<!-- Include Bootstrap CSS for styling -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@endsection

@section('scripts')
<!-- Include jQuery and Bootstrap JS for functionality -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
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
    padding: 20px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: bold;
}

.card-text {
    font-size: 1rem;
    margin-bottom: 5px;
}
</style>

}
