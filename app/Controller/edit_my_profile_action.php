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
$realtorId = $_GET["realtor_id"];

$data = [
    'realtor_title' => $_POST["fullName"] ?? "",
    'email' => $_POST["emailAddress"] ?? "",
    'phone_number' => $_POST["phoneNumber"] ?? "",
    'realtor_sub_title' => $_POST["companyName"] ?? "",
];
foreach ($data as $key => $value) {

    if ($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
}
$database = new Firestore_honeydoo();
$database->updateRealtorInfo($realtorId, $finalData);
header("Location: ../View/my_profile.php");

