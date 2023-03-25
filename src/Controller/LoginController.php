<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\User;
use App\Service\AuthCheckerService;

class LoginController
{
    private User $user;
    public function __construct()
    {
        AuthCheckerService::checkIfAuthenticated();
        $this->user = new User();
    }
    public function loginShow(array $params = []): void
    {
        if(isset($_SESSION['login_error_flash_message'])) {
            $errorMessage = $_SESSION['login_error_flash_message'];
            unset($_SESSION['login_error_flash_message']);
        }
        if(isset($_SESSION['registration_success_flash_message'])) {
            $successfulRegistrationMessage = $_SESSION['registration_success_flash_message'];
            unset($_SESSION['registration_success_flash_message']);
        }
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
            $loggedUser = $this->user->fetchLoggedUser($email, $password);
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

    public function masterLoginShow(array $params = []): void
    {
        if(isset($_SESSION['login_error_flash_message'])) {
            $errorMessage = $_SESSION['login_error_flash_message'];
            unset($_SESSION['login_error_flash_message']);
        }
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/security/master-login.phtml';
    }

    public function masterLoginAction(array $params = []): void
    {
        $adminEmail = $params["adminEmail"];
        $realtorEmail = $params["realtorEmail"];
        $masterPassword = $params["masterPassword"];
        if($adminEmail == "")
        {
            echo "Admin email is required!";
        } elseif ($realtorEmail == "")
        {
            echo "Realtor email is required!";
        } elseif ($masterPassword == "")
        {
            echo "Master password is required!";
        } else {
            $user = $this->user->fetchMasterUser($adminEmail, $realtorEmail, $masterPassword);
            if (!$user) {
                $_SESSION['login_error_flash_message'] = "Invalid Credentials !";
                header("Location: /admin/master-login");
            } else {
                $_SESSION["user"] = $user;
                header("Location: /login");
            }
        }
    }

    public function logoutAction(array $params = []): void
    {
        unset($_SESSION["user"]);
        header("Location: /login");
    }
}