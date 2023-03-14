<?php

namespace App\Entity;

use App\DBConfig;
use Google\Cloud\Firestore\CollectionReference;

class Story
{
    protected $db;
    private CollectionReference $collection;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
        $this->collection = $this->db->collection('blogPost');
    }
    public function findAllByUser($userId) {
        $res = [];
        $query = $this->collection->orderBy('date', 'DESC')->where('realtor_id', '=', $userId);
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
        $blogRef = $this->collection->document($id);
        return $blogRef->update($data);
    }

    public function delete($id)
    {
        $blogRef = $this->collection->document($id);
        return $blogRef->delete();
    }
}