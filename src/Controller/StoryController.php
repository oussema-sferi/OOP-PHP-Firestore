<?php

declare(strict_types=1);

namespace App\Controller;
use App\Service\UserChecker;
use App\Entity\Story;
use App\Entity\User;
use JetBrains\PhpStorm\NoReturn;

class StoryController
{
    private Story $story;
    private User $user;
    private string $loggedUserId;
    public function __construct()
    {
        UserChecker::checkUser();
        $this->story = new Story();
        $this->user = new User();
        $this->loggedUserId = $_SESSION["user"]["realtor_id"];
    }

    #[NoReturn] public function list(array $params = []): void
    {
        $stories = $this->story->findAllByUser($this->loggedUserId);
        $realtor = $this->user->fetchUserById($this->loggedUserId);
        require_once __DIR__ . '/../../templates/stories/list.phtml';
        die();
    }
}