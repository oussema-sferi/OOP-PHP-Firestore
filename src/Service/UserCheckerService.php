<?php

namespace App\Service;

class UserCheckerService
{
    public static function checkUser()
    {
        if(!isset($_SESSION["user"]))
        {
            header("Location: /login");
        } else {
            if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
            {
                header("Location: /admin/users/list");
            }
        }
    }
}