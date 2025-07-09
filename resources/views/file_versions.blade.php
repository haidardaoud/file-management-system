@extends('layouts.layout') {{-- Layout بدون الشريط العلوي --}}

@section('content')
<div class="container my-5">
    <h2 class="text-center text-uppercase mb-4"><strong>Version History for {{ $file->name }}</strong></h2>
    <div class="divider-custom">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
        <div class="divider-custom-line"></div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Version</th>
                        <th>File Path</th>
                        <th>Timestamp</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($versions && count($versions) > 0)
                        @foreach($versions as $index => $version)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge bg-primary">{{ $version->version_number }}</span></td>
                                <td class="text-truncate" style="max-width: 300px;">{{ $version->file_path }}</td>
                                <td>{{ $version->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('download.file.version', $version->id) }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-muted">No versions available.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
