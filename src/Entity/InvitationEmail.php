<?php

namespace App\Entity;

use App\DBConfig;

class InvitationEmail
{
    protected $db;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
    }


    public function fetchEmail()
    {
        $query = $this->db->collection('invitation_email')->document('invitation_email');
        return $query->snapshot();
    }


    public function updateContent($data)
    {
        $email = $this->db->collection('invitation_email')->document('invitation_email');
        return $email->update($data);
    }
}