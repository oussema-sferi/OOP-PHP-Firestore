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
    /*$firstName1 = $_POST["firstName1"];
    $lastName1 = $_POST["lastName1"];
    $email1 = $_POST["email1"];
    $phoneNumber1 = $_POST["phoneNumber1"];
    $firstName2 = $_POST["firstName2"];
    $lastName2 = $_POST["lastName2"];
    $email2 = $_POST["email2"];
    $phoneNumber2 = $_POST["phoneNumber2"];
    $address1 = $_POST["address1"];
    $address2 = $_POST["address2"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $zipCode = $_POST["zipCode"];
    $homeType = $_POST["homeType"];
    $notes = $_POST["notes"];*/
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
        /*'date' => new Timestamp(new DateTime()),*/
    ];
    $database = new Firestore_honeydoo();
    $database->createNewClientCollection($data);
    header("Location: ../View/clients_list.php");

