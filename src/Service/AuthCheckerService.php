<?php

namespace App\Service;

class AuthCheckerService
{
    public static function checkIfNotAuthenticated(): void
    {
        if(!isset($_SESSION["user"]))
        {
            header("Location: /login");
        }
    }

    public static function checkIfAuthenticated(): void
    {
        if(isset($_SESSION["user"]))
        {
            header("Location: /stories/list");
        }
    }

    public static function checkIfRealtor(): void
    {
        self::checkIfNotAuthenticated();
        if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] != "ROLE_USER")
        {
            header("Location: /admin/realtors/list");
        }
    }
    public static function checkIfAdmin(): void
    {
        self::checkIfNotAuthenticated();
        if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] != "ROLE_ADMIN")
        {
            header("Location: /stories/list");
        }
    }
}