<?php

namespace App\Entity;

use App\DBConfig;
use Google\Cloud\Firestore\CollectionReference;

class InvitationEmail
{
    protected $db;
    private CollectionReference $collection;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
        $this->collection = $this->db->collection('invitation_email');
    }


    public function fetchEmail()
    {
        $query = $this->collection->document('invitation_email');
        return $query->snapshot();
    }


    public function updateEmail($data)
    {
        $email = $this->collection->document('invitation_email');
        return $email->update($data);
    }
}