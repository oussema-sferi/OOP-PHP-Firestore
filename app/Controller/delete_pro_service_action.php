<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;
use DateTime;
use Google\Cloud\Core\Timestamp;

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: users_list.php");
    }
}
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$proServiceId = $_GET["pro_service_id"];
$database = new Firestore_honeydoo();
$database->deleteProService($proServiceId);

$loggedRealtorId = $_SESSION["user"]["realtor_id"];
$helper = new HelperService();
$realtorLinkedPortalClients = $database->fetchUserClients($loggedRealtorId);
$allMobileAppClients = $database->fetchAllMobileAppClients();
$helper->clientCheckAndSaveSignUpDate($realtorLinkedPortalClients, $allMobileAppClients, $database);
// Here comes the push notifications
$realtorLinkedMobileClientsTokens = [];
foreach ($realtorLinkedPortalClients as $portalClient)
{
    if(isset($portalClient->notification_token) && trim($portalClient->notification_token) !== "")
    {
        $realtorLinkedMobileClientsTokens[] = $portalClient->notification_token;
    }
}
$notificationParameters = [
    "title" => "HoneyDoo Alert",
    "body" => "Your realtor has removed a recommended home pro."
];
$redirectUrl = "$baseUrl/app/View/pro-services/list.php";
if(count($realtorLinkedMobileClientsTokens) > 0) {
    $helper->sendFCM($realtorLinkedMobileClientsTokens, $notificationParameters, $redirectUrl);
} else {
    header("Location: ../View/pro-services/list.php");
}

