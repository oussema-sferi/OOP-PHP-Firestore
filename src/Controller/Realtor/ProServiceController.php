<?php

declare(strict_types=1);

namespace App\Controller\Realtor;
use App\Entity\PortalClient;
use App\Entity\ProService;
use App\Service\AuthCheckerService;
use App\Service\HelperService;
use App\Entity\User;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ProServiceController
{
    private string $loggedUserId;
    private string $baseUri;
    private string $noImagePath;
    private PortalClient $client;
    private ProService $proService;
    public function __construct()
    {
        AuthCheckerService::checkIfRealtor();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $this->noImagePath = $this->baseUri . "/public/uploaded-images/pro-services/no-image/no-image-available.jpg";
        $this->client = new PortalClient();
        $this->proService = new ProService();
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $user = new User();
        $proServices = $this->proService->findAllByUser($this->loggedUserId);
        $realtor = $user->fetchUserById($this->loggedUserId);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/pro-services/list.phtml';
        die();
    }

    #[NoReturn] public function addForm(array $params = []): void
    {
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/pro-services/new.phtml';
        die();
    }

    #[NoReturn] public function createAction(array $params = []): void
    {
        $image = $_FILES["image"]["name"];
        if(trim($image) !== "")
        {
            $imagePath =  "/public/uploaded-images/pro-services/" . md5(uniqid()) . $image;
            $imagePath = str_replace(" ", "", $imagePath);
            move_uploaded_file(
                $_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
            );
            $imageDbLink = $imagePath;
        } else {
            $imageDbLink = "";
        }

        $data = [
            'company_name' => $_POST["companyName"],
            'company_email' => $_POST["companyEmail"],
            'company_phone_number' => $_POST["companyPhoneNumber"],
            'company_website_link' => $_POST["companyWebsiteLink"],
            'homePro_type' => $_POST["homeProType"],
            'comments' => $_POST["comments"],
            'my_notes' => $_POST["myNotes"],
            'realtor_id' => $this->loggedUserId,
            'img' => $imageDbLink,
            'date' => new Timestamp(new DateTime()),
        ];
        $this->proService->create($data);
        $redirectUri = "/pro-services/list";
        $notificationParameters = [
            "title" => "HoneyDoo Alert",
            "body" => "Your realtor has added a new recommended home pro. Click here to learn more."
        ];
        // Here comes the push notifications
        $helper = new HelperService();
        $helper->clientCheckAndSaveSignUpDate($this->client, $this->loggedUserId, $notificationParameters, $redirectUri);
    }

    #[NoReturn] public function editForm(array $params = []): void
    {
        $id = $params['id'];
        $proService = $this->proService->find($id);
        $image = isset($proService["img"]) && trim($proService["img"]) !== '' && file_exists($_SERVER["DOCUMENT_ROOT"] . $proService["img"]) ? $this->baseUri . $proService["img"] : $this->noImagePath;
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/pro-services/edit.phtml';
        die();
    }

    #[NoReturn] public function editSaveAction(array $params = []): void
    {
        $image = $_FILES["image"]["name"];
        if( trim($image) !== "")
        {
            $imagePath =  "/public/uploaded-images/pro-services/" . md5(uniqid()) . $_FILES["image"]["name"];
            $imagePath = str_replace(" ", "", $imagePath);
            move_uploaded_file(
                $_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $imagePath
            );
            $imageDbLink = $imagePath;
        } else {
            $imageDbLink = "";
        }
        $id = $params["id"];
        $data = [
            'company_name' => $params["companyName"] ?? "",
            'company_email' => $params["companyEmail"] ?? "",
            'company_phone_number' => $params["companyPhoneNumber"] ?? "",
            'company_website_link' => $params["companyWebsiteLink"] ?? "",
            'homePro_type' => $params["homeProType"] ?? "",
            'comments' => $params["comments"] ?? "",
            'my_notes' => $params["myNotes"] ?? "",
            'realtor_id' => $this->loggedUserId,
            'img' => $imageDbLink,
            'date' => new Timestamp(new DateTime()),
        ];
        $finalData = [];
        foreach ($data as $key => $value)
        {
            if($value !== "") $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->proService->update($id, $finalData);
        header("Location: /pro-services/list");
    }

    #[NoReturn] public function showAction(array $params = []): void
    {
        $id = $params["id"];
        $proService = $this->proService->find($id);
        $image = isset($proService["img"]) && trim($proService["img"]) !== '' && file_exists($_SERVER["DOCUMENT_ROOT"] . $proService["img"]) ? $this->baseUri . $proService["img"] : $this->noImagePath;
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/pro-services/show.phtml';
        die();
    }

    #[NoReturn] public function deleteAction(array $params = []): void
    {
        $id = $params["id"];
        $this->proService->delete($id);
        $redirectUri = "/pro-services/list";
        $notificationParameters = [
            "title" => "HoneyDoo Alert",
            "body" => "Your realtor has removed a recommended home pro."
        ];
        // Here comes the push notifications
        $helper = new HelperService();
        $helper->clientCheckAndSaveSignUpDate($this->client, $this->loggedUserId, $notificationParameters, $redirectUri);
    }

    #[NoReturn] public function importFromFileAction(array $params = []): void
    {
        $fileFullPath = $_SERVER['DOCUMENT_ROOT'] . "/public/uploaded-files/pro-services/" . md5(uniqid()) . $_FILES["excelHomeProsfile"]["name"];
        move_uploaded_file(
            $_FILES["excelHomeProsfile"]["tmp_name"], $fileFullPath
        );
        $reader = IOFactory::createReaderForFile($fileFullPath);
        $spreadsheet = $reader->load($fileFullPath);
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
                'realtor_id' => $this->loggedUserId
            ];
            $allData[] = $rowData;
        }
        foreach ($allData as $row)
        {
            $this->proService->create($row);
        }
        $redirectUri = "/pro-services/list";
        $notificationParameters = [
            "title" => "HoneyDoo Alert",
            "body" => "Your realtor has added new recommended home pros. Click here to learn more."
        ];
        // Here comes the push notifications
        $helper = new HelperService();
        $helper->clientCheckAndSaveSignUpDate($this->client, $this->loggedUserId, $notificationParameters, $redirectUri);
    }

    #[NoReturn] public function templateDownloadAction(array $params = []): void
    {
        $filename = $_SERVER["DOCUMENT_ROOT"] . "/public/uploaded-files/templates/import-pro-services.xlsx";
        $helper = new HelperService();
        $helper->templateDownload($filename);
        header("Location: /pro-services/list");
        die();
    }

    public function exportToCsv(array $params = []): void
    {
        $proServices = $this->proService->findAllByUser($this->loggedUserId);
        $data = [];
        $data[] = ['Company Name', 'Company Email', 'Company Phone Number', 'Company Website Link', 'Home Pro Type', 'Comments', 'My Notes'];
        foreach ($proServices as $service) {
            $data[] = [
                'Company Name' => $service->company_name ?? "",
                'Company Email' => $service->company_email ?? "",
                'Company Phone Number' => $service->company_phone_number ?? "",
                'Company Website Link' => $service->company_website_link ?? "",
                'Home Pro Type' => $service->homePro_type ?? "",
                'Comments' => $service->comments ?? "",
                'My Notes' => $service->my_notes ?? "",
            ];
        }
        $sheetName = 'my_pro_services_'. date_create()->format('d-m-y');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->fromArray($data);
        $sheet->setTitle($sheetName);
        $writer = new Xls($spreadsheet);
        $filePath = $_SERVER["DOCUMENT_ROOT"] . "/public/downloaded-files/pro-services-to-csv/" . $sheetName . "_" . uniqid() . '.xls';
        $writer->save($filePath);
        // Return the Excel file as an attachment
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            unlink($filePath);
        }
    }
}