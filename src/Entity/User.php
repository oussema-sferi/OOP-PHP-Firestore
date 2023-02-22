<?php

namespace App\Entity;

use App\DBConfig;

class User
{
    protected $db;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
    }
    public function fetchLoggedUser($email, $userPassword)
    {
        $query = $this->db->collection('realtor')->where('email', '=', $email);
        $documents = $query->documents();

        foreach ($documents as $document) {
            if ($document->exists()) {
                if (password_verify($userPassword, $document->data()["password"]))
                {
                    return $document->data();
                }
            } else {
                return false;
            }
        }
    }

    public function fetchUserById($id)
    {
        $query = $this->db->collection('realtor')->where('realtor_id', '=', $id);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $document->data();
            } else {
                return false;
            }
        }

    }

    public function checkIfUserExists($email)
    {
        $query = $this->db->collection('realtor')->where('email', '=', $email);
        return !$query->documents()->isEmpty();
    }

    public function createNewUser($data)
    {
        $docRef = $this->db->collection('realtor')->add($data);
        return $docRef->id();
    }

    public function setUserId($id)
    {
        $realtorRef = $this->db->collection('realtor')->document($id);
        return $realtorRef->update([['path' => 'realtor_id', 'value' => $id]]);
    }
}