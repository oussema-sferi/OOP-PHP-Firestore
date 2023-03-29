<?php

declare(strict_types=1);

namespace App\Controller\Realtor;
use App\Entity\InvitationEmail;
use App\Entity\PortalClient;
use App\Service\HelperService;
use App\Service\MailerService;
use App\Service\AuthCheckerService;
use App\Entity\Story;
use App\Entity\User;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class PortalClientController
{
    private string $loggedUserId;
    private string $baseUri;
    private readonly User $user;
    private PortalClient $client;
    private InvitationEmail $invitationEmail;
    public function __construct()
    {
        AuthCheckerService::checkIfRealtor();
        if(isset($_SESSION["user"]["realtor_id"])) $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $this->user = new User();
        $this->client = new PortalClient();
        $this->invitationEmail = new InvitationEmail();
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $realtorClients = $this->client->fetchPortalClients($this->loggedUserId);
        $allMobileAppClients = $this->client->fetchMobileAppClients();
        $helper = new HelperService();
        $helper->clientCheckAndSaveSignUpDate($this->client, $this->loggedUserId, [], "", false);
        $clients = $this->client->fetchPortalClients($this->loggedUserId);
        $realtor = $this->user->fetchUserById($this->loggedUserId);
        if(isset($_SESSION['portal_clients_success_flash_message'])) {
            $successFlashMessage = $_SESSION['portal_clients_success_flash_message'];
            unset($_SESSION['portal_clients_success_flash_message']);
        }
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/clients/list.phtml';
        die();
    }

    #[NoReturn] public function addForm(array $params = []): void
    {
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/clients/new.phtml';
        die();
    }

    #[NoReturn] public function createAction(array $params = []): void
    {
        $data = [
            'first_name_1' => $params["firstName1"],
            'last_name_1' => $params["lastName1"],
            'email_1' => $params["email1"],
            'phone_1' => $params["phoneNumber1"],
            'first_name_2' => $params["firstName2"],
            'last_name_2' => $params["lastName2"],
            'email_2' => $params["email2"],
            'phone_2' => $params["phoneNumber2"],
            'address_1' => $params["address1"],
            'address_2' => $params["address2"],
            'city' => $params["city"],
            'state' => $params["state"],
            'zip' => $params["zipCode"],
            'home_type' => $params["homeType"],
            'notes' => $params["notes"],
            'is_subscribed' => true,
            'is_deleted' => false,
            'realtor_id' => $this->loggedUserId,
            'created_at' => new Timestamp(new DateTime()),
        ];
        // Create and save new client in DB
        $this->client->create($data);
        $_SESSION['portal_clients_success_flash_message'] = "Your client has just been created successfully !";
        header("Location: /clients/list");
    }

    #[NoReturn] public function editForm(array $params = []): void
    {
        $id = $params['id'];
        $client = $this->client->find($id);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/clients/edit.phtml';
        die();
    }

    #[NoReturn] public function editSaveAction(array $params = []): void
    {
        $id = $params["id"];
        $data = [
            'first_name_1' => $params["firstName1"],
            'last_name_1' => $params["lastName1"],
            'email_1' => $params["email1"],
            'phone_1' => $params["phoneNumber1"],
            'first_name_2' => $params["firstName2"],
            'last_name_2' => $params["lastName2"],
            'email_2' => $params["email2"],
            'phone_2' => $params["phoneNumber2"],
            'address_1' => $params["address1"],
            'address_2' => $params["address2"],
            'city' => $params["city"],
            'state' => $params["state"],
            'zip' => $params["zipCode"],
            'home_type' => $params["homeType"],
            'notes' => $params["notes"],
            'realtor_id' => $_SESSION["user"]["realtor_id"]
        ];
        $finalData = [];
        foreach ($data as $key => $value)
        {
            $finalData[] = ['path' => $key, 'value' => $value];
        }
        $this->client->update($id, $finalData);
        $_SESSION['portal_clients_success_flash_message'] = "Your client has just been updated successfully !";
        header("Location: /clients/list");
    }

    #[NoReturn] public function showAction(array $params = []): void
    {
        $id = $params["id"];
        $client = $this->client->find($id);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/realtor/clients/show.phtml';
        die();
    }

    #[NoReturn] public function deleteAction(array $params = []): void
    {
        $id = $params["id"];
        $this->client->delete($id);
        $_SESSION['portal_clients_success_flash_message'] = "Your client has just been deleted successfully !";
        header("Location: /clients/list");
        die();
    }

    #[NoReturn] public function deleteSelectedClientsAction(array $params = []): void
    {
        $clientsIds = json_decode($params["selectedClients"]);
        foreach ($clientsIds as $clientId)
        {
            $this->client->delete($clientId);
        }
        $text = count($clientsIds) === 1 ? "Client has" : "Clients have";
        $_SESSION['portal_clients_success_flash_message'] = "$text just been deleted successfully !";
        header("Location: /clients/list");
        die();
    }

    #[NoReturn] public function emailsUnsubscriptionAction(array $params = []): void
    {
        $clientId = $params["id"];
        $data = [
            ['path' => 'is_subscribed', 'value' => false]
        ];
        $this->client->update($clientId, $data);
        $flashMessage = "You have just been unsubscribed successfully !";
        if(isset($_SESSION["user"]["realtor_id"]))
        {
            $_SESSION['story_success_flash_message'] = $flashMessage;
        } else {
            $_SESSION['registration_success_flash_message'] = $flashMessage;
        }
        header("Location: /");
        die();
    }

    #[NoReturn] public function sendEmailInvitationToClientAction(array $params = []): void
    {
        $clientsIds = json_decode($params["selectedClients"]);
        $clientsArray = [];
        foreach ($clientsIds as $clientId)
        {
            $clientsArray[] = $this->client->find($clientId);
        }
        $subscribedClients = [];
        foreach ($clientsArray as $client)
        {
            if($client["is_subscribed"]) $subscribedClients[] = $client;
        }
        $unsubscriptionLink = $this->baseUri . "/clients/emails-unsubscription?id=";
        $email = $this->invitationEmail->fetchEmail();
        $emailContent = $email["content"];
        $emailSubject = $email["subject"];
        $realtor = $this->user->fetchUserById($this->loggedUserId);
        $realtorInfo = [
            "{{realtor_name}}" => $realtor["realtor_title"],
            "{{realtor_photo}}" => "'" . $realtor["realtor_photo"] . "'",
            "{{current_year}}" => date("Y"),
        ];
        foreach ($realtorInfo as $key => $value)
        {
            $emailContent = str_replace($key, $value, $emailContent);
        }
        $emailSubject = str_replace("{{realtor_name}}", $_SESSION["user"]["realtor_title"], $emailSubject);
        foreach ($subscribedClients as $subscribedClient)
        {
            $mailer = new MailerService();
            $emailContent = str_replace("{{unsubscribe}}", $unsubscriptionLink . $subscribedClient->id(), $emailContent);
            $mailer->sendInvitationMail($emailContent, $emailSubject, [$subscribedClient["email_1"], $subscribedClient["email_2"]]);
            $data = [['path' => 'email_invite_sent_at', 'value' => new Timestamp(new DateTime())]];
            $this->client->update($subscribedClient->id(), $data);
        }
        $text = count($subscribedClients) === 1 ? "Invitation has" : "Invitations have";
        $_SESSION['portal_clients_success_flash_message'] = "$text just been sent successfully !";
        header("Location: /clients/list");
        die();
    }

    #[NoReturn] public function templateDownloadAction(array $params = []): void
    {
        $filename = $_SERVER["DOCUMENT_ROOT"] . "/public/uploaded-files/templates/import-clients.xlsx";
        $helper = new HelperService();
        $helper->templateDownload($filename);
        header("Location: /clients/list");
        die();
    }

    #[NoReturn] public function importFromFileAction(array $params = []): void
    {
        $path = "";
        if (isset($_POST["submit"]))
        {
            $fileFullPath = $_SERVER['DOCUMENT_ROOT'] . "/public/uploaded-files/clients/" . md5(uniqid()) . $_FILES["excelclientsfile"]["name"];
            move_uploaded_file(
                $_FILES["excelclientsfile"]["tmp_name"], $fileFullPath
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
                    'is_subscribed' => true,
                    'is_deleted' => false,
                    'realtor_id' => $this->loggedUserId,
                    'created_at' => new Timestamp(new DateTime()),
                ];
                $allData[] = $rowData;
            }
            foreach ($allData as $client)
            {
                $this->client->create($client);
            }
            $_SESSION['portal_clients_success_flash_message'] = "Your clients have just been imported successfully !";
        }
        header("Location: /clients/list");
        die();
    }

    public function exportToCsv(array $params = []): void
    {
        $realtorClients = $this->client->fetchPortalClients($this->loggedUserId);
        $data = [];
        $data[] = ['Client Name', 'Address', 'City', 'State', 'Created At', 'Email Invite Sent At', 'Client Signed-up At'];
        foreach ($realtorClients as $client) {
            $data[] = [
                'Client Name' => $client->first_name_1 . " " . $client->last_name_1 ?? "",
                'Address' => $client->address_1 ?? "",
                'City' => $client->city ?? "",
                'State' => $client->state ?? "",
                'Created At' => isset($client->created_at) && $client->created_at !== "" ? $client->created_at->get()->format("m-d-Y") : "",
                'Email Invite Sent At' => isset($client->email_invite_sent_at) && $client->email_invite_sent_at !== "" ? $client->email_invite_sent_at->get()->format("m-d-Y") : "",
                'Client Signed-up At' => isset($client->mobile_app_signed_up_at) && $client->mobile_app_signed_up_at !== "" ? $client->mobile_app_signed_up_at->get()->format("m-d-Y") : ""
            ];
        }
        $sheetName = 'my_clients_'. date_create()->format('d-m-y');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->fromArray($data);
        $sheet->setTitle($sheetName);
        $writer = new Xls($spreadsheet);
        $filePath = $_SERVER["DOCUMENT_ROOT"] . "/public/downloaded-files/clients-to-csv/" . $sheetName . "_" . uniqid() . '.xls';
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