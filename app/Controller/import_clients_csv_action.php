<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use DateTime;
use Google\Cloud\Core\Timestamp;
use PhpOffice\PhpSpreadsheet\IOFactory;

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: users_list.php");
    }
}
$path = "";
if (isset($_POST["submit"]))
{
    $filePath =  "/app/Ressources/import_files/clients/" . md5(uniqid()) . $_FILES["excelclientsfile"]["name"];
    move_uploaded_file(
        $_FILES["excelclientsfile"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $filePath
    );
    $reader = IOFactory::createReaderForFile($_SERVER['DOCUMENT_ROOT'] . $filePath);
    $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT'] . $filePath);
    $activeSheet = $spreadsheet->getActiveSheet();
    $activeSheetArray = $activeSheet->toArray(null, true, true, true, true);
    array_shift($activeSheetArray);
    $allData = [];
    foreach ($activeSheetArray as $row)
    {
        $rowData = [
            'first_name_1' => $row["A"],
            'last_name_1' => $row["B"],
            'email_1' => $row["C"],
            'phone_1' => $row["D"],
            'first_name_2' => $row["E"],
            'last_name_2' => $row["F"],
            'email_2' => $row["G"],
            'phone_2' => $row["H"],
            'address_1' => $row["I"],
            'address_2' => $row["J"],
            'city' => $row["K"],
            'state' => $row["L"],
            'zip' => $row["M"],
            'home_type' => $row["N"],
            'notes' => $row["O"],
            'realtor_id' => $_SESSION["user"]["realtor_id"]
        ];
        $allData[] = $rowData;
    }
    $database = new Firestore_honeydoo();
    foreach ($allData as $row)
    {
        $database->createNewClientCollection($row);
    }
}
header("Location: ../View/clients_list.php");

