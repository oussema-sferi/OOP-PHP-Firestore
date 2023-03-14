<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\User;

class ResetPasswordController
{
    public function showRequestPasswordAction(array $params = []): void
    {
        require_once __DIR__ . '/../../templates/security/reset-password/request.phtml';
    }

}