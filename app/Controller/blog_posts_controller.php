<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;

/*require_once "../Model/Firestore_honeydoo.php";*/
/*$test = new Authentication();
$test->loginCheck();*/
$database = new Firestore_honeydoo('blogPosts');
/*$blogPosts = $database->fetchBlogPosts();*/
    /*if(!$user)
    {
        $_SESSION['login_error_flash_message'] = "Invalid Credentials !";
    } else {
        $_SESSION["user"] = $user;
    }
    header("Location: ../View/dashboard.php");*/
