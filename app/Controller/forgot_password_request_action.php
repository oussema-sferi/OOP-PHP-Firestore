<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use DateTime;
use Google\Cloud\Core\Timestamp;
use App\Model\Firestore_honeydoo;
use App\Service\MailerService;

$email = $_POST["email"];
$database = new Firestore_honeydoo();
$user = $database->fetchUserByEmail($email);
if(!$user)
{
    header("Location: ../View/forgot_password/check_email.php");
}

try {
    /*var_dump($user["realtor_id"]);
    die();*/
    $token = bin2hex(random_bytes(50));
    /*var_dump($token);
    die();*/


    $data = [
        'realtor_email' => $user["email"],
        'token' => $token,
        'requested_at' => new Timestamp(new DateTime()),
    ];
    $database = new Firestore_honeydoo();
    $database->saveResetPasswordToken($data);
    $resetPasswordEmail = $database->fetchResetEmail();
    $emailContent = $resetPasswordEmail["content"];
    $emailSubject = $resetPasswordEmail["subject"];
    $mailer = new MailerService();

    $resetPasswordLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/app/Controller/forgot_password_validate_token_action.php?token=" . $token;

    $msg = "Hi there, click on this <a href=" . $resetPasswordLink . ">link</a> to reset your password on our site";
    $msg = wordwrap($msg,70);
    /*$headers = "From: info@examplesite.com";
    mail($to, $subject, $msg, $headers);
    header('location: pending.php?email=' . $email);*/
    $mailer->sendResetPasswordMail($msg, $emailSubject, $user["email"]);
    /*echo "email was just sent";*/
    header("Location: ../View/forgot_password/check_email.php");

} catch (\Exception $e) {
    header("Location: ../View/forgot_password/check_email.php");
}
