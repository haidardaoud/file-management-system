@extends('layouts.layout')

@section('content')
<section class="page-section" id="add-user">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Add Users to Group</h2>
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
            <div class="divider-custom-line"></div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form id="searchForm" method="GET" action="{{ route('searchUsers', ['groupId' => $groupId]) }}">
            <div class="row mb-3">
                <div class="col-md-9">
                    <input type="text" name="query" class="form-control" placeholder="Search users by name">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                </div>
            </div>
        </form>

        <form id="addUsersForm" action="{{ route('storeUsers', ['groupId' => $groupId]) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Select Users to Add</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}">
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary">Add Selected Users</button>
                </div>
            </div>
        </form>
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

<script>
    $(document).ready(function() {
        $('#addUsersForm').on('submit', function(e) {
            if ($('input[name="user_ids[]"]:checked').length === 0) {
                e.preventDefault();
                alert('Please select at least one user to add.');
            }
        });
    });
</script>
@endsection

<style>
.card {
    transition: transform 0.3s, box-shadow 0.3s;
    margin-bottom: 20px;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.3);
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.btn-primary {
    background-color: blue;
    border-color: blue;
}

.btn-primary:hover {
    background-color: darkblue;
    border-color: darkblue;
}

.table-responsive {
    margin-top: 20px;
}

@media (max-width: 767.98px) {
    .page-section-heading {
        font-size: 1.5rem;
    }

    .btn-primary {
        font-size: 1rem;
    }
}
</style>
