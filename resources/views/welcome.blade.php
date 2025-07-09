<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <!-- Lottie Player CDN -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            color: #333;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 4vh; /* Use vh for top padding */
        }
        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .header {
            font-family: 'Dancing Script', cursive;
            font-size: 3vh; /* Use vh for font size */
            color: #2c3e50;
            margin-bottom: 4vh; /* Use vh for bottom margin */
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            width: 90vw; /* Use vw for width */
            max-width: 600px;
            padding: 0 2vw; /* Use vw for padding */
        }
        .button {
            padding: 1vh 2vw; /* Use vh and vw for padding */
            font-size: 2vh; /* Use vh for font size */
            color: white;
            background-color: #3498db;
            border: none;
            border-radius: 0.375em;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <lottie-player class="background-animation" src="https://lottie.host/d79ca852-18ce-430e-872c-144eac8f6486/1t5WuKSYyr.json" background="transparent" speed="1" loop autoplay></lottie-player>
    <div class="header">Welcome to Our System</div>
    <div class="buttons">
        <a href="{{ route('login') }}" class="button" style="margin-right: auto;">Login</a>
        <a href="{{ route('register') }}" class="button" style="margin-left: auto;">Register</a>
    </div>
</body>
</html>
