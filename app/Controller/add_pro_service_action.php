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
$loggedRealtorId = $_SESSION["user"]["realtor_id"];
$imagePath =  "/app/pro_services_images/" . md5(uniqid()) . $_FILES["proServiceImage"]["name"];
move_uploaded_file(
    $_FILES["proServiceImage"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
);
$data = [
    'company_name' => $_POST["companyName"],
    'company_email' => $_POST["companyEmail"],
    'company_phone_number' => $_POST["companyPhoneNumber"],
    'company_website_link' => $_POST["companyWebsiteLink"],
    'homePro_type' => $_POST["homeProType"],
    'comments' => $_POST["comments"],
    'my_notes' => $_POST["myNotes"],
    'realtor_id' => $loggedRealtorId,
    'img' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $imagePath,
    'date' => new Timestamp(new DateTime()),
];
$database = new Firestore_honeydoo();
$database->createNewProService($data);

// Here comes the push notifications
$helper = new HelperService();
$realtorLinkedPortalClients = $database->fetchUserClients($loggedRealtorId);
$allMobileAppClients = $database->fetchAllMobileAppClients();
$helper->clientCheckAndSaveSignUpDate($realtorLinkedPortalClients, $allMobileAppClients, $database);
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
    "body" => "Your realtor has added a new recommended home pro. Click here to learn more."
];
$redirectUrl = "../View/pro_services.php";
if(count($realtorLinkedMobileClientsTokens) > 0) {
    $helper->sendFCM($realtorLinkedMobileClientsTokens, $notificationParameters, $redirectUrl);
} else {
    header("Location: ../View/pro_services.php");
}
