<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;

$clientDocId = $_GET["id"];
$database = new Firestore_honeydoo();
/*$client = $database->fetchClientCollectionById($clientDocId);*/
$data = [
    'is_subscribed' => false,
];
foreach ($data as $key => $value)
{
    if($value != "") $finalData[] = ['path' => $key, 'value' => $value];
}
$database->updateClientCollection($clientDocId, $finalData);
header("Location: ../View/unsubscription_confirmation.php");