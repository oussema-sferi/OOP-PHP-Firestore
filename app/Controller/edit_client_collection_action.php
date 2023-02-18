<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
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
$clientCollectionId = $_GET["client_collection_id"];
$finalData = [];
$data = [
    'first_name_1' => $_POST["firstName1"],
    'last_name_1' => $_POST["lastName1"],
    'email_1' => $_POST["email1"],
    'phone_1' => $_POST["phoneNumber1"],
    'first_name_2' => $_POST["firstName2"],
    'last_name_2' => $_POST["lastName2"],
    'email_2' => $_POST["email2"],
    'phone_2' => $_POST["phoneNumber2"],
    'address_1' => $_POST["address1"],
    'address_2' => $_POST["address2"],
    'city' => $_POST["city"],
    'state' => $_POST["state"],
    'zip' => $_POST["zipCode"],
    'home_type' => $_POST["homeType"],
    'notes' => $_POST["notes"],
    'realtor_id' => $_SESSION["user"]["realtor_id"]
];
foreach ($data as $key => $value)
{
    if($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
}
$database = new Firestore_honeydoo();
$database->updateClientCollection($clientCollectionId, $finalData);
header("Location: ../View/clients/list.php");
