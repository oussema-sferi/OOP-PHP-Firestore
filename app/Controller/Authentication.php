<?php

namespace App\Controller;
use App\Model\Firestore_honeydoo;

class Authentication
{
    private $database;
    private $email;
    private $password;
    public function __construct()
    {
        $this->database = new Firestore_honeydoo('realtor');
        $this->email = $_POST["email"];
        $this->password = $_POST["password"];
    }


    public function loginCheck()
    {
        if($this->email == "")
        {
            echo "Email is required!";
        } elseif ($this->password == "")
        {
            echo "Password is required!";
        } else {
            $user = $this->database->fetchUser($this->email, $this->password);
            if(!$user)
            {
                $_SESSION['login_error_flash_message'] = "Invalid Credentials !";
            } else {
                $_SESSION["user"] = $user;
            }
            header("Location: ../View/dashboard.php");
        }
    }
}