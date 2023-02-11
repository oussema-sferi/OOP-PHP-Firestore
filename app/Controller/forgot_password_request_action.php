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
    die();
}

try {
    $token = bin2hex(random_bytes(50));
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
    $emailContent = str_replace("{{reset_password_link}}", $resetPasswordLink, $emailContent);
    $mailer->sendResetPasswordMail($emailContent, $emailSubject, $user["email"]);
    header("Location: ../View/forgot_password/check_email.php");
} catch (\Exception $e) {
    header("Location: ../View/forgot_password/check_email.php");
}
