<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\ProService;
use App\Service\HelperService;
use App\Service\UserCheckerService;
use App\Entity\User;
use App\Entity\Client;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProServiceController
{
    private Client $client;
    private ProService $proService;
    private string $loggedUserId;
    private string $baseUri;
    public function __construct()
    {
        UserCheckerService::checkUser();
        $this->proService = new ProService();
        $this->client = new Client();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $user = new User();
        $proServices = $this->proService->findAllByUser($this->loggedUserId);
        $realtor = $user->fetchUserById($this->loggedUserId);
        require_once __DIR__ . '/../../templates/pro-services/list.phtml';
        die();
    }

    #[NoReturn] public function addForm(array $params = []): void
    {
        require_once __DIR__ . '/../../templates/pro-services/new.phtml';
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
            $imageDbLink = $this->baseUri . $imagePath;
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
        require_once __DIR__ . '/../../templates/pro-services/edit.phtml';
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
            $imageDbLink = $this->baseUri . $imagePath;
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
        require_once __DIR__ . '/../../templates/pro-services/show.phtml';
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

    #[NoReturn] public function importCsvAction(array $params = []): void
    {
        $filePath =  "/public/uploaded-files/pro_services/" . md5(uniqid()) . $_FILES["excelHomeProsfile"]["name"];
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
}