<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use App\Service\MailerService;
use function Google\Cloud\Debugger\showUsageAndDie;

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
/*var_dump($clientsArray[0]->id());
die();*/
$subscribedClients = [];
foreach ($clientsArray as $client)
{
    if($client["is_subscribed"] == true) $subscribedClients[] = $client;
}

$emailAddresses = [];
foreach ($subscribedClients as $client)
{
    $emailAddresses[] = $client["email_1"];
    $emailAddresses[] = $client["email_2"];
}

$unsubscriptionLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/app/Controller/unsubscribe_action.php?id=";

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
foreach ($subscribedClients as $client)
{
    $emailContent = str_replace("{{unsubscribe}}", $unsubscriptionLink . $client->id(), $emailContent);
    $mailer->sendInvitationMail($emailContent, [$client["email_1"], $client["email_2"]]);
}

header("Location: ../View/clients_list.php");

