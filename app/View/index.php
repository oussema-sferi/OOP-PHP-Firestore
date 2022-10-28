<?php

if(!isset($_SESSION["user"]))
{
    header("Location: View/login.php");
} else {
    header("Location: View/dashboard.php");
}