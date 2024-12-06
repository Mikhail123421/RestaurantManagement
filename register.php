<?php
include("dbConection.php"); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user input
    $firstName = isset($_POST['f_name']) ? $_POST['f_name'] : '';
    $lastName = isset($_POST['l_name']) ? $_POST['l_name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $role = 'guest'; // Default role

    // Check if the passwords match
    if ($password !== $confirmPassword) {
        echo "رمز عبور ها یکی نیست";
        exit;
    }

    // Check if the email already exists in the database
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE EMAIL = :email");
    $stmt->execute(['email' => $email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists) {
        echo "<script>alert('این ایمیل قبلاً ثبت شده است'); window.location.href='register.php';</script>";
        exit;
    }

    // Hash the password before saving it to the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user data into the database
    $stmt = $pdo->prepare("INSERT INTO users (F_NAME, L_NAME, EMAIL, PASSWORD, ROLE) 
                           VALUES (:f_name, :l_name, :email, :password, :role)");
    try {
        $stmt->execute([
            'f_name' => $firstName,
            'l_name' => $lastName,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);

        echo "ثبت نام با موفقیت انجام شد";
    } catch (PDOException $e) {
        echo "خطا در ثبت نام: " . $e->getMessage();
    }
}
?>

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
  }

  form button {
    width: 100px;
    height: 25px;
    border-radius: 8px;
    margin-top: 15px;
    box-shadow: none;
  }
</style>

<form method="post" action="" dir="rtl">
  <div>
    <label for="f_name">نام</label>
    <div class="col-8">
      <input id="f_name" name="f_name" type="text" required>
    </div>
  </div>
  <div>
    <label for="l_name">نام خانوادگی</label>
    <div class="col-8">
      <input id="l_name" name="l_name" type="text" required>
    </div>
  </div>
  <div>
    <label for="email">ایمیل</label>
    <div class="col-8">
      <input id="email" name="email" type="email" required>
    </div>
  </div>
  <div>
    <label for="password">رمز عبور</label>
    <div class="col-8">
      <input id="password" name="password" type="password" required>
    </div>
  </div>
  <div>
    <label for="confirm_password">تکرار رمز عبور</label>
    <div class="col-8">
      <input id="confirm_password" name="confirm_password" type="password" required>
    </div>
  </div> 
  <div>
    <div>
      <button name="submit" type="submit">ثبت نام</button>
      <a href="login.php" id="logoutBtn">
        <button type="button">ورود</button>
      </a>
    </div>
  </div>
</form>  
