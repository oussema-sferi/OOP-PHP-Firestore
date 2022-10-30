<?php
namespace App\View;

unset($_SESSION["user"]);
header("Location: login.php");
