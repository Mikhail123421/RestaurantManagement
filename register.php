
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user input
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = 'guest';

    // Check if the passwords match
    if ($password !== $confirmPassword) {
        echo "رمز عبور ها یکی نیست";
        exit;
    }

    // Check if the email already exists in the users.json file
    $filePath = 'users.json';
    if (file_exists($filePath)) {
        $existingData = json_decode(file_get_contents($filePath), true);
    } else {
        $existingData = [];
    }

    // Check if the email is already in use
    foreach ($existingData as $user) {
        if ($user['email'] === $email) {
            echo "<script>alert('این ایمیل قبلاً ثبت شده است'); window.location.href='register.php';</script>";
            exit;
        }
    }

    // Create new user data
    $userData = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'password' => $password,
        'role' => $role  // Encrypt the password
    ];

    // Add the new user data to the array
    $existingData[] = $userData;

    // Save the updated data back to the file
    file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    echo "ثبت نام با موفقیت انجام شد";
}
?>





<style>
  form
  {
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    align-items: center;
  }

  form input
  {
    width: 500px;
    height: 40px;
    border-radius: 10px;
  }
  form button
  {
    width: 100px;
    height: 25px;
    border-radius: 8px;
    margin-top: 15px;
    box-shadow: none;
  }

  
</style>
<form method="post" action="" dir="rtl">
  <div>
    <label for="first_name">نام</label> 
    <div class="col-8">
      <input id="first_name" name="first_name" type="text" required>
    </div>
  </div>
  <div>
    <label for="last_name">نام خانوادگی</label> 
    <div class="col-8">
      <input id="last_name" name="last_name" type="text" required>
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
    <div >
      <button name="submit" type="submit">ثبت نام</button>
      <a href="login.php" id="logoutBtn">
    <button type="button">ورود</button>
</a>
    </div>
  </div>
</form>
