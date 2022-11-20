<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use DateTime;
use Google\Cloud\Core\Timestamp;

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
}
$clientCollectionId = $_GET["client_collection_id"];
$database = new Firestore_honeydoo();
$database->deleteClientCollection($clientCollectionId);
header("Location: ../View/clients_list.php");

