<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;

$email = $_POST["email"];
$database = new Firestore_honeydoo();
$user = $database->fetchUserByEmail($email);
if($user)
{
    echo "yes";
}
header("Location: ../View/forgot_password/check_email.php");
