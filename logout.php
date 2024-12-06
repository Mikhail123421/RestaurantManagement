<?php
session_start(); 

// حذف همه مقادیر سشن
session_unset();
session_destroy();

// ابطال کوکی‌ها
setcookie('user_email', '', time() - 3600, "/");
setcookie('user_id', '', time() - 3600, "/");

// انتقال به صفحه ورود
header('Location: login.php');
exit;
?>
