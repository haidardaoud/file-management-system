@extends('layouts.layout')

@section('content')
<section class="page-section" id="mygroup">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">My Groups</h2>
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        <div class="row">
            @forelse($groups as $group)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <img src="{{ $group->image ? asset('storage/' . basename($group->image)) : asset('images/default.jpg') }}" class="card-img-top" alt="{{ $group->name }}">
                        <div class="card-body">
                            <h5 class="card-title d-flex justify-content-between align-items-center">
                                {{ $group->name }}
                                <a href="{{ route('view', ['id' => $group->id]) }}" class="btn btn-primary btn-sm">View</a>
                            </h5>
                            <p class="card-text">{{ $group->description ?? 'No description available.' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">You are not a member of any groups yet.</p>
            @endforelse
        </div>
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

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.btn-success {
    background-color: green;
    border-color: green;
}

.btn-success:hover {
    background-color: darkgreen;
    border-color: darkgreen;
}
</style>
