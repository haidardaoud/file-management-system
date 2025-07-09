<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .header {
            text-align: center;
            margin: 50px 0;
        }
        .welcome-box {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        .group-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 0 auto;
            max-width: 400px;
        }
        .sidebar {
            position: absolute;
            top: 150px; /* Adjusted for header space */
            right: 20px;
            width: 250px; /* Increased width */
            background: #ffffff;
            padding: 15px;
            border-radius: 0.25rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            display: block;
            margin-bottom: 15px; /* Increased spacing */
            padding: 10px; /* Added padding */
            text-decoration: none;
            color: white;
            background-color: #007bff; /* Button color */
            border-radius: 5px;
            transition: background-color 0.2s;
            text-align: center;
        }
        .sidebar a:hover {
            background-color: #0056b3;
        }
        .btn-custom {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- جملة الترحيب -->
        <div class="welcome-box">
            <h1>Welcome To Our System</h1>
        </div>

        <!-- البوكس الخاص بإضافة مجموعة -->
        <div class="group-box text-center">
            <form action="{{ route('home') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-custom">Add Group</button>
            </form>
        </div>

        <!-- القائمة الجانبية -->
        <aside class="sidebar">
            <h5>Menu</h5>
            <ul class="list-group">
                <li class="list-group-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="list-group-item"><a href="{{ route('home') }}">View Groups</a></li>
                <li class="list-group-item"><a href="{{ route('home') }}">Users</a></li>
                <li class="list-group-item"><a href="{{ route('home') }}">Settings</a></li>
                <li class="list-group-item"><a href="{{ route('home') }}">Logout</a></li>
            </ul>
        </aside>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
