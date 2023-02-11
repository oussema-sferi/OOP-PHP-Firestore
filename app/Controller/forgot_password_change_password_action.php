<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use DateTime;
use Google\Cloud\Core\Timestamp;
$password = trim($_POST["password"]);
$confirmPassword = trim($_POST["confirmPassword"]);
if(!isset($_SESSION['token']))
{
    header("Location: ../View/forgot_password/request.php");
}
$tokenFromSession = $_SESSION['token'];
$database = new Firestore_honeydoo();
$userFromDB = $database->fetchTokenFromDb($tokenFromSession);
if(!$userFromDB)
{
    header("Location: ../View/forgot_password/request.php");
}
if($password !== $confirmPassword)
{
    $_SESSION['change_password_error_flash_message'] = "Your passwords did not match! Please try again";
    header("Location: ../View/forgot_password/reset.php");
    die();
}
if (!(strlen($password) >= 8 && strpbrk($password, "!#$@.,:;()"))){
    // next code block
    $_SESSION['change_password_error_flash_message'] = "Your password is not strong enough. Please use another one and try again.";
    header("Location: ../View/forgot_password/reset.php");
    die();
}
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$user = $database->fetchUserByEmail($userFromDB["realtor_email"]);
$finalData[] = ['path' => 'password', 'value' => $hashedPassword];
$database->updateRealtorInfo($user["realtor_id"], $finalData);
$database->deleteResetRequest($userFromDB["token"]);
header("Location: ../View/forgot_password/reset_password_confirmation.php");
