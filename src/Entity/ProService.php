<?php

namespace App\Entity;


use App\DBConfig;
use Google\Cloud\Firestore\CollectionReference;

class ProService
{
    protected $db;
    private CollectionReference $collection;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
        $this->collection = $this->db->collection('realtor_home_pro_service');
    }

    public function findAllByUser($userId)
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

    public function create($data)
    {
        return $this->collection->add($data);
    }

    public function find($docId)
    {
        $query = $this->collection->document($docId);
        return $query->snapshot();
    }

    public function update($id, $data)
    {
        $proService = $this->collection->document($id);
        return $proService->update($data);
    }

    public function delete($id)
    {
        $proService = $this->collection->document($id);
        return $proService->delete();
    }
}