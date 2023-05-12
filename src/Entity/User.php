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

    public function markRealtorAsDeleted($realtorId)
    {
        $realtor = $this->db->collection('realtor')->document($realtorId);
        return $realtor->update([['path' => 'is_deleted', 'value' => true]]);
    }

    public function restoreRealtor($realtorId)
    {
        $realtor = $this->db->collection('realtor')->document($realtorId);
        return $realtor->update([['path' => 'is_deleted', 'value' => false]]);
    }

    public function fetchMasterUser($adminEmail, $realtorEmail, $masterPassword)
    {
        $query = $this->db->collection('realtor')->where('email', '=', $realtorEmail);
        $documents = $query->documents();
        $secondQuery = $this->db->collection('realtor')->where('email', '=', $adminEmail);
        $secondDocuments = $secondQuery->documents();
        foreach ($secondDocuments as $document2) {
            if ($document2->exists()) {
                if (password_verify($masterPassword, $document2->data()["master_password"]))
                {
                    foreach ($documents as $document1) {
                        if ($document1->exists()) {
                            return $document1->data();
                        } else {
                            return false;
                        }
                    }
                }
            } else {
                return false;
            }
        }
    }

    public function validateActualMasterPassword($adminEmail, $masterPassword)
    {
        $query = $this->db->collection('realtor')->where('email', '=', $adminEmail);
        $documents = $query->documents();

        foreach ($documents as $document) {
            if ($document->exists()) {
                if (password_verify($masterPassword, $document->data()["master_password"]))
                {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    public function fetchDeletedRealtors()
    {
        $res = [];
        $query = $this->db->collection('realtor')
            ->where('is_deleted', '=', true)
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

    public function showInApp($realtorId)
    {
        $realtor = $this->db->collection('realtor')->document($realtorId);
        return $realtor->update([['path' => 'show_in_app', 'value' => true]]);
    }

    public function hideFromApp($realtorId)
    {
        $realtor = $this->db->collection('realtor')->document($realtorId);
        return $realtor->update([['path' => 'show_in_app', 'value' => false]]);
    }
}