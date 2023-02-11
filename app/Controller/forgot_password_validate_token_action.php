<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;

$token = trim($_GET["token"]);
if($token == "")
{
    header("Location: ../View/forgot_password/request.php");
    die();
}
$database = new Firestore_honeydoo();
$resetRequest = $database->fetchTokenFromDb($token);

if(!$resetRequest)
{
    header("Location: ../View/forgot_password/request.php");
    die();
}
$_SESSION["token"] = $token;
header("Location: ../View/forgot_password/reset.php");