<?php
namespace App\Controller;
require_once "../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;
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
    $loggedRealtorId = $_SESSION["user"]["realtor_id"];
    $filePath =  "/app/Ressources/import_files/pro_services/" . md5(uniqid()) . $_FILES["excelHomeProsfile"]["name"];
    move_uploaded_file(
        $_FILES["excelHomeProsfile"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $filePath
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
            'company_name' => $row["A"],
            'company_email' => $row["B"],
            'company_phone_number' => $row["C"],
            'company_website_link' => $row["D"],
            'homePro_type' => $row["E"],
            'comments' => $row["F"],
            'my_notes' => $row["G"],
            'date' => new Timestamp(new DateTime()),
            'realtor_id' => $loggedRealtorId
        ];
        $allData[] = $rowData;
    }
    $database = new Firestore_honeydoo();
    foreach ($allData as $row)
    {
        $database->createNewProService($row);
    }
    // Here comes the push notifications
    $helper = new HelperService();
    $realtorLinkedPortalClients = $database->fetchUserClients($loggedRealtorId);
    $allMobileAppClients = $database->fetchAllMobileAppClients();
    $helper->clientCheckAndSaveSignUpDate($realtorLinkedPortalClients, $allMobileAppClients, $database);
    $realtorLinkedMobileClientsTokens = [];
    foreach ($realtorLinkedPortalClients as $portalClient)
    {
        if(isset($portalClient->notification_token) && trim($portalClient->notification_token) !== "")
        {
            $realtorLinkedMobileClientsTokens[] = $portalClient->notification_token;
        }
    }
    $notificationParameters = [
        "title" => "HoneyDoo Alert",
        "body" => "Your realtor has added a new recommended home pro. Click here to learn more."
    ];
    $redirectUrl = "../View/pro_services.php";
    if(count($realtorLinkedMobileClientsTokens) > 0) {
        $helper->sendFCM($realtorLinkedMobileClientsTokens, $notificationParameters, $redirectUrl);
    } else {
       header("Location: ../View/pro-services/list.php");
    }
}

