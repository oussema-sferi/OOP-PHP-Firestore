<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;

$token = trim($_GET["token"]);
/*var_dump(trim($token));
die();*/
$database = new Firestore_honeydoo();
$resetRequest = $database->fetchTokenFromDb($token);

if(!$resetRequest)
{
    var_dump("token is invalid!");
    die();
    header("Location: ../View/forgot_password/request.php");
}

/*foreach ($data as $key => $value)
{
    $finalData[] = ['path' => $key, 'value' => $value];
}
$database->updateClientCollection($clientDocId, $finalData);*/
header("Location: ../View/unsubscription_confirmation.php");