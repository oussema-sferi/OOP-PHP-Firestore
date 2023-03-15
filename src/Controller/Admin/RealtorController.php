<?php

declare(strict_types=1);

namespace App\Controller\Admin;
use App\Service\HelperService;
use App\Entity\User;
use Google\Cloud\Core\Timestamp;
use JetBrains\PhpStorm\NoReturn;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RealtorController
{
    private User $user;
    private string $loggedUserId;
    private string $baseUri;
    public function __construct()
    {
        $this->user = new User();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
        $this->baseUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    #[NoReturn] public function listAction(array $params = []): void
    {
        $realtors = $this->user->fetchUsersByRole("ROLE_USER");
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/admin/realtors/list.phtml';
        die();
    }

    #[NoReturn] public function addForm(array $params = []): void
    {
        require_once $_SERVER["DOCUMENT_ROOT"] . '/templates/pro-services/new.phtml';
        die();
    }
}