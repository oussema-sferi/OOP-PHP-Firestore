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

    public function fetchTokenFromDb($token)
    {
        $query = $this->db->collection('reset_password_request')->where('token', '=', $token);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $document->data();
            } else {
                return false;
            }
        }
    }

    public function deleteResetRequest($token)
    {
        $query = $this->db->collection('reset_password_request')->where('token', '=', $token);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $this->db->collection('reset_password_request')->document($document->id())->delete();
            } else {
                return false;
            }
        }
    }

    public function updateResetEmail($data)
    {
        $emailRef = $this->db->collection('reset_password_email')->document('reset_password_email');
        return $emailRef->update($data);
    }
}