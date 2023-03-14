<?php

namespace App\Entity;

use App\DBConfig;
use Google\Cloud\Firestore\CollectionReference;

class ResetPassword
{
    protected $db;
    private CollectionReference $collection;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
    }

    public function saveResetPasswordToken($data)
    {
        return  $this->db->collection('reset_password_request')->add($data);
    }

    public function fetchResetEmail()
    {
        $query = $this->db->collection('reset_password_email')->document('reset_password_email');
        return $query->snapshot();
    }
}