<?php

class UserSession
{

    public function startSession()
    {
        session_start();
    }


    public function destroySession()
    {
        session_unset();
        session_destroy();
    }

 
    public function removeCookies()
    {
        setcookie('user_email', '', time() - 3600, "/");
        setcookie('user_id', '', time() - 3600, "/");
    }

  
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
}


$userSession = new UserSession();


$userSession->startSession();


$userSession->destroySession();
$userSession->removeCookies();

$userSession->redirect('login.php');
?>
