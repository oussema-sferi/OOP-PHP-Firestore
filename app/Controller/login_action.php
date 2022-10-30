<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;

$email = $_POST["email"];
$password = $_POST["password"];
$database = new Firestore_honeydoo();
if($email == "")
{
    echo "Email is required!";
} elseif ($password == "")
{
    echo "Password is required!";
} else {
    $user = $database->fetchUser($email, $password);
    if(!$user)
    {
        $_SESSION['login_error_flash_message'] = "Invalid Credentials !";
    } else {
        $_SESSION["user"] = $user;
    }
    header("Location: ../View/blog_posts.php");
}