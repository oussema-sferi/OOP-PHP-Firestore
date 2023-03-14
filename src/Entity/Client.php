<?php

namespace App\Entity;

use App\DBConfig;

class Client
{
    protected $db;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
    }


    public function fetchPortalClients($userId): array
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

    public function fetchMobileAppClients(): array
    {
        $res = [];
        $query = $this->db->collection('user');
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

    public function create($data)
    {
        return $this->db->collection('realtor_clients')->add($data);
    }

    public function find($id)
    {
        $query = $this->db->collection('realtor_clients')->document($id);
        return $query->snapshot();
    }

    public function update($id, $data)
    {
        $client = $this->db->collection('realtor_clients')->document($id);
        return $client->update($data);
    }
}