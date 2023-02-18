<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use App\Service\MailerService;
use DateTime;
use Google\Cloud\Core\Timestamp;

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: ../View/users_list.php");
    }
}
$clientsDocsIds = json_decode($_POST["selectedClients"]);
$clientsArray = [];
$database = new Firestore_honeydoo();
foreach ($clientsDocsIds as $clientId)
{
$clientsArray[] = $database->fetchClientCollectionById($clientId);
}

$subscribedClients = [];
foreach ($clientsArray as $client)
{
    if($client["is_subscribed"] == true) $subscribedClients[] = $client;
}

$unsubscriptionLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/app/Controller/unsubscribe_action.php?id=";

$email = $database->fetchEmailContent();
$emailContent = $email["content"];
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$realtorInfo = [
    "{{realtor_name}}" => $realtor["realtor_title"],
    "{{realtor_photo}}" => "'" . $realtor["realtor_photo"] . "'",
    "{{current_year}}" => date("Y"),
];
foreach ($realtorInfo as $key => $value)
{
    $emailContent = str_replace($key, $value, $emailContent);
}

$email = $database->fetchEmailContent();
$emailSubject = $email["subject"];
$emailSubject = str_replace("{{realtor_name}}", $_SESSION["user"]["realtor_title"], $emailSubject);
/*var_dump($subscribedClients[0]->id());
die();*/
foreach ($subscribedClients as $client)
{
    $mailer = new MailerService();
    $emailContent = str_replace("{{unsubscribe}}", $unsubscriptionLink . $client->id(), $emailContent);
    $mailer->sendInvitationMail($emailContent, $emailSubject, [$client["email_1"], $client["email_2"]]);
    $data = [['path' => 'email_invite_sent_at', 'value' => new Timestamp(new DateTime())]];
    $database->updateClientCollection($client->id(), $data);
}
header("Location: ../View/clients/list.php");

