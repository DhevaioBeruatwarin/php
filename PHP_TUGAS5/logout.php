<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - To-Do List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #1e1e2f;
            color: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            width: 100%;
            background: #2d2d44;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .profile-photo {
            width: 500px;
            height: 250px;
            object-fit: cover;
            border-radius: 16px;
            border: 3px solid #ff9a76;
        }

        .header-text h1 {
            font-size: 28px;
            margin: 0;
            text-align: center;
            color: #ff9a76;
        }
          .header-text h2 {
            font-size: 28px;
            margin: 0;
            text-align: center;
            color: #ff9a76;
        }
          .header-text h3 {
            font-size: 28px;
            margin: 0;
            text-align: center;
            color: #ff9a76;
        }


        .container {
            background: #2a2a3d;
            padding: 30px;
            margin-top: 40px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0,0,0,0.6);
        }

        .container h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .container p {
            font-size: 16px;
            margin-top: 10px;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #ff9a76;
            padding: 10px 20px;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background-color: #ff7a50;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <img src="Goodbye.jpg" alt="Goodbye" class="profile-photo">
            <div class="header-text">
                <h1>Goodbye Buddy!!!</h1>
                <h2>Don't be sad<h2>
                <h3>May GOD Always be upon you...<h3>
            </div>
        </div>
    </header>

    <div class="container">
        <h2>Berhasil Logout</h2>
        <p>Anda telah keluar dari sistem.<br>
        Kembali ke halaman login dalam <span id="countdown">15</span> detik...</p>
        <a href="login.php" class="btn">Login Sekarang</a>
    </div>

    <script>
        let countdown = 15;
        const countdownElement = document.getElementById('countdown');
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = 'login.php';
            }
        }, 1000);
    </script>
</body>
</html>
