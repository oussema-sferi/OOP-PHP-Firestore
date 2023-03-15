<?php

namespace App\Entity;

use App\DBConfig;
use Google\Cloud\Firestore\CollectionReference;

class Client
{
    protected $db;
    private CollectionReference $collection;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
        $this->collection = $this->db->collection('realtor_clients');
    }


    public function fetchPortalClients($userId): array
    {
        $res = [];
        $query = $this->collection->where('realtor_id', '=', $userId);
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
        return $this->collection->add($data);
    }

    public function find($id)
    {
        $query = $this->collection->document($id);
        return $query->snapshot();
    }

    public function update($id, $data)
    {
        $client = $this->collection->document($id);
        return $client->update($data);
    }

    public function delete($id)
    {
        $client = $this->collection->document($id);
        return $client->delete();
    }

    public function markClientAsDeleted($clientId)
    {
        $client = $this->db->collection('realtor_clients')->document($clientId);
        return $client->update([['path' => 'is_deleted', 'value' => true]]);
    }

    public function fetchAllClients(): array
    {
        $res = [];
        $query = $this->db->collection('realtor_clients')->where('is_deleted', '!=', true);
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