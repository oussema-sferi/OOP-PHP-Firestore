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

    public function validateActualPassword($userEmail, $password)
    {
        $query = $this->db->collection('realtor')->where('email', '=', $userEmail);
        $documents = $query->documents();

        foreach ($documents as $document) {
            if ($document->exists()) {
                if (password_verify($password, $document->data()["password"]))
                {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    public function fetchUsersByRole($role)
    {
        $res = [];
        $query = $this->db->collection('realtor')
            ->where('role', '=', $role)
            ->orderBy('is_deleted')
            ->where('is_deleted', '!=', true)
            ->orderBy('date', 'DESC')
        ;
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                $obj_merged = (object) array_merge(
                    ["doc_id" => $document->id()], (array) $document->data());
                $res[] = $obj_merged;
            }
        }
        return $res;
    }

    public function fetchUserAddedClients($userId): array
    {
        $res = [];
        $query = $this->db->collection('realtor_clients')->where('realtor_id', '=', $userId);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                $obj_merged = (object) array_merge(
                    ["doc_id" => $document->id()], (array) $document->data());
                $res[] = $obj_merged;
            }
        }
        return $res;
    }
}