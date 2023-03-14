<?php

namespace App\Entity;

use App\DBConfig;
use Google\Cloud\Firestore\CollectionReference;

class User
{
    protected $db;
    private CollectionReference $collection;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
        $this->collection = $this->db->collection('realtor');
    }


    public function fetchLoggedUser($email, $userPassword)
    {
        $query = $this->collection->where('email', '=', $email);
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
        $query = $this->collection->where('realtor_id', '=', $id);
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
        $query = $this->collection->where('email', '=', $email);
        return !$query->documents()->isEmpty();
    }

    public function createNewUser($data)
    {
        $docRef = $this->collection->add($data);
        return $docRef->id();
    }

    public function setUserId($id)
    {
        $realtorRef = $this->collection->document($id);
        return $realtorRef->update([['path' => 'realtor_id', 'value' => $id]]);
    }

    public function update($id, $data)
    {
        $realtor = $this->collection->document($id);
        return $realtor->update($data);
    }

    public function findByEmail($email)
    {
        $query = $this->db->collection('realtor')->where('email', '=', $email);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $document->data();
            } else {
                return false;
            }
        }
    }
}