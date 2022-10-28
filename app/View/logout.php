<?php
namespace App\View;

unset($_SESSION["user"]);
header("Location: dashboard.php");
