<?php

namespace App\Entity;

use App\DBConfig;
use Google\Cloud\Firestore\CollectionReference;

class MobileAppClient
{
    protected $db;
    private CollectionReference $collection;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
        $this->collection = $this->db->collection('user');
    }

    public function fetchAllMobileAppClients(): array
    {
        $res = [];
        $query = $this->collection->where('is_delete', '!=', true);
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

    public function fetchRealtorMobileAppClients($realtorId): array
    {
        $res = [];
        $query = $this->collection->where('realtor_id', '=', $realtorId);
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

    public function find($id)
    {
        $query = $this->collection->where('uid', '=', $id);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $document->data();
            } else {
                return false;
            }
        }
    }

    public function update($id, $data)
    {
        $client = $this->collection->document($id);
        return $client->update($data);
    }

    public function markAsDeleted($id)
    {
        $client = $this->collection->document($id);
        return $client->update([['path' => 'is_delete', 'value' => true]]);
    }
}