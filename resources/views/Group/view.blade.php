<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@extends('layouts.layout')

@section('content')
<section class="page-section" id="group-view">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ $group->name }}</h2>
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-4 text-center">
                <img src="{{ $group->image ? asset('storage/' . basename($group->image)) : asset('images/default.jpg') }}" class="img-fluid rounded" alt="{{ $group->name }}" style="max-height: 300px;">
            </div>
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Group Details</h5>
                        <p class="card-text">{{ $group->description ?? 'No description available.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Members Table -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Members</span>
                        @if($isAdmin)
                            <a href="{{ route('adduser', ['groupId' => $group->id]) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Add Members
                            </a>
                            <a href="{{ route('memberLogs', ['groupId' => $group->id]) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-history"></i> Member Logs
                            </a>

                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group->groupMember as $member)
                                    <tr>
                                        <td>{{ $member->user->name }}</td>
                                        <td>{{ $member->user->email }}</td>
                                        <td>
                                            @if($isAdmin && !$member->isOwner)
                                                <form action="{{ route('removeUser', ['groupId' => $group->id, 'userId' => $member->user_id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </form>
                                            @elseif($member->isOwner)
                                                <span class="text-muted">Admin</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- <a href="{{  route('fileLogs', ['fileId' => $group->id]) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-tasks"></i> Logs File
            </a> --}}
            <!-- Files Table -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Files</span>

                        @if($isAdmin)
                            <div>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addFileModal">
                                    <i class="fas fa-plus"></i> Add File
                                </button>
                                <a href="{{ route('pendingRequests', ['groupId' => $group->id]) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-tasks"></i> Pending Requests
                                </a>


                            </div>
                        @else
                            <a href="{{ route('requestFileUpload', ['groupId' => $group->id]) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-file-upload"></i> Request to Upload File
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAllFiles"></th>
                                    <th>File Name</th>
                                    <th>Uploaded By</th>
                                    <th>Status</th>
                                    <th>Logs</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($group->files as $file)
                                    <tr>
                                        <td><input type="checkbox" class="fileCheckbox" name="file_ids[]" value="{{ $file->id }}"></td>
                                        <td>{{ $file->name }}</td>
                                        <td>{{ $file->uploadedBy->name }}</td>
                                        <td>
                                            @if(!$file->isAvailable)
                                                @if(session()->has('checkedInFileIds') && in_array($file->id, session()->get('checkedInFileIds')))
                                                    <span class="text-warning">Locked by You</span>
                                                @else
                                                    <span class="text-danger">Under Modification</span>
                                                @endif
                                            @else
                                                <span class="text-success">Available</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('fileLogs', ['fileId' => $file->id]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-file-alt"></i> Log File
                                            </a>
                                        </td>

                                        <td>
                                            @if(!$file->isAvailable && in_array($file->id, session()->get('checkedInFileIds', [])))
                                                <form action="{{ route('checkOutFiles', ['fileId' => $file->id]) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="input-group">
                                                        <input type="file" name="updated_file" required class="form-control mb-2">
                                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                                            <i class="fas fa-upload"></i> Check-Out
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                            <a href="{{ route('viewFileVersions', ['fileId' => $file->id]) }}" class="btn btn-info btn-sm mt-2">
                                                <i class="fas fa-history"></i> View Versions
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-sm" id="checkInFilesButton">
                            <i class="fas fa-lock"></i> Check In Files
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add File Modal -->
            <div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="addFileModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addFileModalLabel">Add File to Group</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
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
                    </div>
                </div>
            </div>
        </div>
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
    <script>
        $(document).ready(function() {
            $('#selectAllFiles').click(function() {
                $('.fileCheckbox').prop('checked', $(this).prop('checked'));
            });

            $('#checkInFilesButton').click(function() {
                var selectedFileIds = [];
                $('.fileCheckbox:checked').each(function() {
                    selectedFileIds.push($(this).val());
                });

                if (selectedFileIds.length === 0) {
                    alert('Please select at least one file.');
                    return;
                }

                $.ajax({
                    url: '{{ route('checkInFile') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        file_ids: selectedFileIds
                    },
                    success: function(response) {
                        response.filePaths.forEach(function(filePath) {
                            const downloadLink = document.createElement('a');
                            downloadLink.href = filePath;
                            downloadLink.download = '';
                            document.body.appendChild(downloadLink);
                            downloadLink.click();
                            document.body.removeChild(downloadLink);
                        });
                        location.reload();
                    },
                    error: function(error) {
                        alert('Error checking in files: ' + error.responseJSON.message);
                    }
                });
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
        width: 100%;
    }

    .img-fluid {
        max-height: 300px;
        object-fit: cover;
        width: 100%;
    }

    .btn-success {
        background-color: green;
        border-color: green;
    }

    .btn-success:hover {
        background-color: darkgreen;
        border-color: darkgreen;
    }

    .btn-danger {
        background-color: red;
        border-color: red;
    }

    .btn-danger:hover {
        background-color: darkred;
        border-color: darkred;
    }

    .text-muted {
        color: #6c757d;
    }

    .file-actions .btn {
        margin-right: 5px;
    }
</style>
