<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use DateTime;
use Google\Cloud\Core\Timestamp;

/*$email = $_POST["emailAddress"];*/
$password = $_POST["password"];
$confirmPassword = $_POST["confirmPassword"];

if($password !== $confirmPassword)
{
    $_SESSION['registration_error_flash_message'] = "Your passwords did not match! Please try again";
    header("Location: ../View/registration.php");
}

if (!(strlen($password) >= 8 && strpbrk($password, "!#$.,:;()"))){
    // next code block
    $_SESSION['registration_error_flash_message'] = "Your password is not strong enough. Please use another one and try again.";
    header("Location: ../View/registration.php");
}

/*$database = new Firestore_honeydoo();
$_SESSION['login_error_flash_message'] = "Invalid Credentials !";

header("Location: ../View/login.php");*/

