<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use App\Service\MailerService;

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: ../View/users_list.php");
    }
}
$emailAddresses = json_decode($_POST["selectedClients"]);
$database = new Firestore_honeydoo();
$email = $database->fetchEmailContent();
$emailContent = $email["content"];
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$realtorInfo = [
    "{{realtor_name}}" => $realtor["realtor_title"],
    "{{realtor_photo}}" => "'" . $realtor["realtor_photo"] . "'",
];
foreach ($realtorInfo as $key => $value)
{
    $emailContent = str_replace($key, $value, $emailContent);
}
$mailer = new MailerService();
$mailer->sendInvitationMail($emailContent, $emailAddresses);
header("Location: ../View/clients_list.php");

