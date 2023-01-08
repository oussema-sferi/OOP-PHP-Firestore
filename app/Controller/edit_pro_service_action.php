<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";

use App\Model\Firestore_honeydoo;
use DateTime;
use Google\Cloud\Core\Timestamp;

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: users_list.php");
    }
}
$finalData = [];
$proServiceId = $_GET["pro_service_id"];
$imagePath = $_FILES["proServiceImage"]["name"] !== "" ? "/app/pro_services_images/" . md5(uniqid()) . $_FILES["proServiceImage"]["name"] : "";
$imageDbLink = $_FILES["proServiceImage"]["name"] !== "" ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $imagePath : "";
if ($imagePath !== "") {
    move_uploaded_file(
        $_FILES["proServiceImage"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
    );
}
$data = [
    'company_name' => $_POST["companyName"] ?? "",
    'company_email' => $_POST["companyEmail"] ?? "",
    'company_phone_number' => $_POST["companyPhoneNumber"] ?? "",
    'company_website_link' => $_POST["companyWebsiteLink"] ?? "",
    'homePro_type' => $_POST["homeProType"] ?? "",
    'comments' => $_POST["comments"] ?? "",
    'my_notes' => $_POST["myNotes"] ?? "",
    'realtor_id' => $_SESSION["user"]["realtor_id"],
    'img' => $imageDbLink,
    'date' => new Timestamp(new DateTime()),
];
foreach ($data as $key => $value) {

    if ($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
}
$database = new Firestore_honeydoo();
$database->updateProService($proServiceId, $finalData);
header("Location: ../View/pro_services.php");

