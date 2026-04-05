<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Page Carousel</title>

    <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    <style>
        /* Reset margin/padding default */
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        /* Carousel container */
        #driverCarousel {
            margin: 25px auto;
            max-width: 500px; /* lebar maksimal carousel */
        }

        /* Carousel item */
        .carousel-item img {
            width: 500px;     /* menyesuaikan lebar container */
            height: 300px;     /* tinggi tetap */
            object-fit: cover; /* agar gambar tidak melar */
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.25);
        }

        /* Navigasi panah */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0,0,0,0.5);
            border-radius: 50%;
            padding: 10px;
        }

        /* Indicators */
        .carousel-indicators [data-bs-target] {
            background-color: #ff6b6b;
        }

        /* Responsive untuk layar kecil */
        @media (max-width: 576px) {
            #driverCarousel {
                max-width: 90%;
                margin: 20px auto;
            }
            .carousel-item img {
                height: 150px;
            }
        }

        @media (max-width: 400px) {
            .carousel-item img {
                height: 120px;
            }
        }
    </style>
</head>
<body>

<div id="driverCarousel" class="carousel slide" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#driverCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#driverCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#driverCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('image/driver1.jpeg') }}" alt="Driver 1">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('image/driver2.jpeg') }}" alt="Driver 2">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('image/driver3.jpeg') }}" alt="Driver 3">
        </div>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#driverCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#driverCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>