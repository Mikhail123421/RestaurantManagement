<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحه داشبورد</title>
    <style>
        /* Reset default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center; /* Horizontally center the content */
            align-items: center; /* Vertically center the content */
            height: 100vh; /* Full viewport height */
            margin: 0;
        }

        .container {
            width: 80%;
            max-width: 500px; /* Limit width for better appearance on large screens */
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        .btn {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%; /* Make buttons take the full width of the container */
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn-logout {
            background-color: #f44336;
            color: white;
        }

        .btn-logout:hover {
            background-color: #e53935;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .btn-secondary {
            background-color: #2196F3;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #1976D2;
        }

        .link {
            text-decoration: none;
            color: #4CAF50;
            display: inline-block;
            margin-top: 15px;
        }

        .link:hover {
            text-decoration: underline;
        }

        a
        {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    session_start(); // شروع سشن
    ?>

    <?php if (isset($_SESSION['user'])): ?>
        <!-- نمایش دکمه خروج اگر کاربر وارد شده باشد -->
        <a href="logout.php" class="btn btn-logout">
            خروج
        </a>

        <?php 
        // بررسی نقش کاربر و نمایش محتوای متفاوت
        if ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="foodList.php" class="btn btn-primary">
                لیست غذاها
            </a>
            <a href="userlist.php" class="btn btn-secondary">
                لیست کاربران
            </a>
        <?php elseif ($_SESSION['user']['role'] === 'guest'): ?>
            <a href="orderFood.php" class="btn btn-primary">
                سفارش غذا
            </a>
        <?php else: ?>
            <!-- محتوای پیش‌فرض در صورتی که نقش کاربر مشخص نباشد -->
            <h2>خوش آمدید</h2>
            <p>لطفاً وارد حساب کاربری خود شوید تا از امکانات استفاده کنید.</p>
        <?php endif; ?>

    <?php else: ?>
        <!-- در صورتی که کاربر وارد نشده باشد -->
        <p>لطفاً وارد حساب کاربری خود شوید.</p>
        <a href="login.php" class="link">ورود</a>
    <?php endif; ?>
</div>

</body>
</html>
