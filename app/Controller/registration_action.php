<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use DateTime;
use Google\Cloud\Core\Timestamp;

$email = $_POST["emailAddress"];
$password = $_POST["password"];
$confirmPassword = $_POST["confirmPassword"];
$database = new Firestore_honeydoo();
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/app/View/";
if($database->checkIfRealtorUserExists($email))
{
    $_SESSION['registration_error_flash_message'] = "This email is already used! Please choose another email and try again";
    header("Location: ../View/registration.php");
    die();
}

if($password !== $confirmPassword)
{
    $_SESSION['registration_error_flash_message'] = "Your passwords did not match! Please try again";
    header("Location: ../View/registration.php");
    die();
}

if (!(strlen($password) >= 8 && strpbrk($password, "!#$@.,:;()"))){
    // next code block
    $_SESSION['registration_error_flash_message'] = "Your password is not strong enough. Please use another one and try again.";
    header("Location: ../View/registration.php");
    die();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
/*var_dump(password_hash($password, PASSWORD_DEFAULT));
die();*/

$data = [
    'email' => $_POST["emailAddress"],
    'password' => $hashedPassword,
    'role' => "ROLE_USER",
    'realtor_title' => $_POST["fullName"],
    'realtor_sub_title' => $_POST["companyName"],
    'phone_number' => $_POST["phoneNumber"],
    'realtor_photo' => "",
    'address_line_1' => "This is a fake address one",
    'address_line_2' => "This is a fake address two",
    'city' => "Fake City",
    'zip_code' => "51426",
    'homePro_type' => "m is simply dummy text of the printing and typesetting industry",
    'realtor_comments' => "is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    'date' => new Timestamp(new DateTime()),
];
$realtorDocumentId = $database->createNewRealtorUser($data);
$database->setRealtorId($realtorDocumentId);
$_SESSION['registration_success_flash_message'] = "Congratulations! Your account has been created successfully. You can use your credentials to login";
header("Location: $baseUrl . 'common/security/login.php'");

