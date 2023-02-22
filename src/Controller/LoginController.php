<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\User;

class LoginController
{
    public function show(array $params = []): void
    {
        require_once __DIR__ . '/../../templates/security/login.phtml';
    }

    public function loginAction(array $params = []): void
    {

        /*$redirectUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/app/View/stories/list.php";*/
        $email = $params["email"];
        $password = $params["password"];
        if($email == "")
        {
            echo "Email is required!";
        } elseif ($password == "")
        {
            echo "Password is required!";
        } else {
            $user = new User();
            $loggedUser = $user->fetchLoggedUser($email, $password);
            if(!$loggedUser)
            {
                $_SESSION['login_error_flash_message'] = "Invalid Credentials !";
            } else {
                $_SESSION["user"] = $loggedUser;
            }
            header("Location: /stories/list");
            die();
        }

    }

    public function logoutAction(array $params = []): void
    {
        unset($_SESSION["user"]);
        header("Location: /login");
    }
}