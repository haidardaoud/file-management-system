<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group</title>
    <!-- روابط Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .page-section {
            padding: 60px 0;
        }

        .divider-custom {
            margin: 1.25rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .divider-custom-line {
            width: 100px;
            height: 1px;
            background-color: #6c757d;
        }

        .divider-custom-icon {
            font-size: 2rem;
            margin: 0 1rem;
            color: #007bff;
        }

        .card {
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<section class="page-section" id="createGroup">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Create a New Group</h2>
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <form id="createGroupForm" action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Group Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter group name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Write a short description..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Group Image</label>
                                <input type="file" id="image" name="image" class="form-control-file" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Group</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- روابط الجافاسكريبت -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
