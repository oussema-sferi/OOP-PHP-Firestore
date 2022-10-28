<?php
require_once "vendor/autoload.php";
use App\Model\Firestore;

$db = new Firestore();
print_r($db->test());