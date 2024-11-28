<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = @$_POST['email'];
    $password = @$_POST['password'];

    // Validate the fields
    if (empty($email) || empty($password)) {
        $errorMessage = "لطفا فیلد ها را تکمیل کنید";
    } else {
        $jsonData = file_get_contents('users.json');
        $users = json_decode($jsonData, true);

        $userFound = false; // Track if the user is found

        foreach ($users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                $userFound = true;
                // Set session data for the logged-in user
                $_SESSION['user'] = [
                    'email' => $user['email'],
                    'role' => $user['role']
                ];

                // Redirect based on the role
                if ($user['role'] === 'admin') {
                    header('Location: foodlist.php');
                    exit;  
                } elseif ($user['role'] === 'guest') {
                    header('Location: orderFood.php');
                    exit; 
                }
            }
        }

        // If user is not found, show error
        if (!$userFound) {
            $errorMessage = "خطا نام کاریری یا پسوورد اشتباه است";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود</title>
    <style>
        form {
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
            align-items: center;
        }

        form input {
            width: 500px;
            height: 40px;
            border-radius: 10px;
            margin-top: 5px;
        }

        form button {
            width: 100px;
            height: 25px;
            border-radius: 8px;
            margin-top: 15px;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">ورود</h1>

    <?php if (isset($errorMessage)): ?>
        <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">ورود</button>
        <button><a href="register.php">ثبت نام</a></button>
    </form>
</body>
</html>
