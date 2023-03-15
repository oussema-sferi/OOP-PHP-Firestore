<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\User;
use App\Service\AuthCheckerService;

class LoginController
{
    public function __construct()
    {
        AuthCheckerService::checkIfAuthenticated();
    }
    public function show(array $params = []): void
    {
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/security/login.phtml';
    }

    public function loginAction(array $params = []): void
    {
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