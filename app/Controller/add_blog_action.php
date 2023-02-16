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
$imagePath =  "/app/blog_posts_images/" . md5(uniqid()) . $_FILES["blogPostImage"]["name"];
$imagePath = str_replace(" ", "", $imagePath);
move_uploaded_file(
    $_FILES["blogPostImage"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
);
$title = $_POST["blogPostTitle"];
$distribution = $_POST["blogPostDistribution"];
$data = [
    'title' => $title,
    'distribution' => $distribution,
    'realtor_id' => $loggedRealtorId,
    'img' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $imagePath,
    'date' => new Timestamp(new DateTime()),
];
$database = new Firestore_honeydoo();

// Create and save new blog post in DB
$database->createNewBlogPost($data);
//
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
if(count($realtorLinkedMobileClientsTokens) > 0) $helper->sendFCM($realtorLinkedMobileClientsTokens);
header("Location: ../View/blog_posts.php");

