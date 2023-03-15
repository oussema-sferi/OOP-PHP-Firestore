<?php

namespace App\Service;

class AdminCheckerService
{
    public static function checkUser(): void
    {
        if(!isset($_SESSION["user"]))
        {
            header("Location: /login");
        } else {
            if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] != "ROLE_ADMIN")
            {
                header("Location: /stories/list");
            }
        }
    }
}