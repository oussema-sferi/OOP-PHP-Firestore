<?php

class SecurityService
{
    public function checkLoggedUser($role) {
        if(!isset($_SESSION["user"]))
        {
            header("Location: login.php");
        } else {
            if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == $role)
            {
                header("Location: users_list.php");
            }
        }
    }
}