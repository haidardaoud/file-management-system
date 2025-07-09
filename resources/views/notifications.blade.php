{{-- @extends('layouts.layout')

@section('title', 'Notifications')

@section('content')

<section class="page-section" id="notifications">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Your Notifications</h2>
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-bell"></i></div>
            <div class="divider-custom-line"></div>
        </div>

        @if($notifications->isEmpty())
            <div class="alert alert-info text-center">No notifications found.</div>
        @else
            <div class="row"> --}}
                {{-- لالالالالالالالالالالالالالالاللالا --}}
                {{-- @foreach($notifications as $notification)
                    @php
                        $notificationData = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
                    @endphp
                    <div class="col-md-6 mb-4">
                        <div class="card notification-card {{ $notification->read_at ? 'bg-white' : 'bg-warning' }} shadow-lg" data-id="{{ $notification->id }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $notificationData['fileName'] ?? 'Notification' }}</h5>
                                <p class="card-text">
                                    <strong>File Name:</strong> {{ $notificationData['fileName'] ?? 'No file name provided.' }} <br>
                                    <strong>Action:</strong> {{ $notificationData['action'] ?? 'No action provided.' }} <br>
                                    <strong>Updated by:</strong> {{ $notificationData['userName'] ?? 'Unknown User' }}
                                </p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>

                                @if(!$notification->read_at)
                                <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Mark as Read</button>
                                </form>

                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach --}}
{{-- this is code for the above تكملة --}}
                {{-- @foreach ($notifications as $notification)
    @php
        // محاولة تحويل البيانات إذا كانت غير مصفوفة
        $notificationData = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
    @endphp
    <div class="col-md-6 mb-4">
        <div class="card notification-card {{ is_null($notification->read_at) ? 'bg-warning' : 'bg-white' }} shadow-lg" data-id="{{ $notification->id }}">
            <div class="card-body">
                <h5 class="card-title">{{ $notificationData['fileName'] ?? 'Notification' }}</h5>
                <p class="card-text">
                    <strong>File Name:</strong> {{ $notificationData['fileName'] ?? 'No file name provided.' }} <br>
                    <strong>Action:</strong> {{ $notificationData['action'] ?? 'No action provided.' }} <br>
                    <strong>Updated by:</strong> {{ $notificationData['userName'] ?? 'Unknown User' }}
                </p>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>

                @if (is_null($notification->read_at))
                    <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Mark as Read</button>
                    </form>

                @endif
            </div>
        </div>
    </div>
@endforeach

            </div>
        @endif

        <form method="POST" action="{{ route('notifications.clearAll') }}" class="mt-4 text-center">
            @csrf
            <button type="submit" class="btn btn-danger">Clear All Notifications</button>
        </form>
    </div>
</section>

@endsection --}}
{{-- this is the testing coded wire: --}}
@extends('layouts.layout')

@section('title', 'Notifications')

@section('content')

<section class="page-section" id="notifications">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Your Notifications</h2>
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-bell"></i></div>
            <div class="divider-custom-line"></div>
        </div>

        @if($notifications->isEmpty())
            <div class="alert alert-info text-center">No notifications found.</div>
        @else
            <div class="row">
                @foreach ($notifications as $notification)
                    @php
                        // Attempt to decode the JSON data
                        $notificationData = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
                    @endphp
                    <div class="col-md-6 mb-4">
                        <div class="card notification-card {{ is_null($notification->read_at) ? 'bg-warning' : 'bg-white' }} shadow-lg" data-id="{{ $notification->id }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $notificationData['fileName'] ?? 'Notification' }}</h5>
                                <p class="card-text">
                                    <strong>File Name:</strong> {{ $notificationData['fileName'] ?? 'No file name provided.' }} <br>
                                    <strong>Action:</strong> {{ $notificationData['action'] ?? 'No action provided.' }} <br>
                                    <strong>Updated by:</strong> {{ $notificationData['userName'] ?? 'Unknown User' }}
                                </p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>

                                @if (is_null($notification->read_at))
                                    <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="mark-as-read-form mt-2">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Mark as Read</button>
                                        {{-- {{ $notification->id }} <!-- Log notification ID for debugging --> --}}
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('notifications.clearAll') }}" class="mt-4 text-center">
            @csrf
            <button type="submit" class="btn btn-danger">Clear All Notifications</button>
        </form>
    </div>
</section>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.mark-as-read-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var form = $(this);
        console.log('Form action:', form.attr('action')); // Log the action URL

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                console.log(response); // Log the response for debugging
                // Change card color to white and remove the button
                form.closest('.notification-card').removeClass('bg-warning').addClass('bg-white');
                form.remove(); // Optionally remove the button or disable it
            },
            error: function(xhr) {
                console.error(xhr); // Log error details for debugging
                alert('Error marking notification as read: ' + xhr.responseJSON.error);
            }
        });
    });
});
</script>
@endsection

@endsection
