<?php
class UserLogin
{
    private $pdo;
    private $errorMessage;

    // Constructor to initialize PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Handle login logic
    public function login($email, $password)
    {
        // Validate the fields
        if (empty($email) || empty($password)) {
            $this->errorMessage = "لطفا فیلد ها را تکمیل کنید";
            return false;
        }

        // Prepare and execute the query to find the user by email
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['PASSWORD'])) {
                // Set cookies for 30 days
                setcookie('user_email', $user['EMAIL'], time() + (30 * 24 * 60 * 60), "/");
                setcookie('user_id', $user['ID'], time() + (30 * 24 * 60 * 60), "/");

                // Set session
                $_SESSION['user'] = [
                    'email' => $user['EMAIL'],
                    'role' => $user['ROLE'],
                    'user_id' => $user['ID']
                ];

                return true; // Successfully logged in
            } else {
                $this->errorMessage = "خطا نام کاریری یا پسوورد اشتباه است";
            }
        } else {
            $this->errorMessage = "خطا نام کاریری یا پسوورد اشتباه است";
        }

        return false; // Login failed
    }

    // Getter for error message
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}

session_start();

// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashborad.php');
    exit;
}

include("dbConection.php");

$userLogin = new UserLogin($pdo);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = @$_POST['email'];
    $password = @$_POST['password'];

    // Attempt to log in
    if ($userLogin->login($email, $password)) {
        // Redirect to dashboard if login is successful
        header('Location: dashborad.php');
        exit;
    } else {
        // Get error message if login fails
        $errorMessage = $userLogin->getErrorMessage();
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

        button {
            width: 100px;
            height: 25px;
            border-radius: 8px;
            margin-top: 15px;
            box-shadow: none;
        }

        form a {
            text-align: center;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">ورود</h1>
    <div style="text-align: center;">

        <?php if (isset($errorMessage)): ?>
            <div style="color: red;"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="ایمیل" required>
            <input type="password" name="password" placeholder="رمز عبور" required>
            <button type="submit">ورود</button>
        </form>

        <a href="register.php">
            <button>ثبت نام</button>
        </a>
    </div>

</body>
</html>
