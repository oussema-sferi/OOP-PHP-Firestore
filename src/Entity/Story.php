<?php

namespace App\Entity;


use App\DBConfig;

class Story
{
    protected $db;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
    }
    public function findAllByUser($userId) {
        $res = [];
        $query = $this->db->collection('blogPost')->orderBy('date', 'DESC')->where('realtor_id', '=', $userId);
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
        return $this->db->collection('blogPost')->add($data);
    }

    public function find($docId)
    {
        $query = $this->db->collection('blogPost')->document($docId);
        return $query->snapshot();
    }

    public function update($id, $data)
    {
        $blogRef = $this->db->collection('blogPost')->document($id);
        return $blogRef->update($data);
    }

    public function delete($id)
    {
        $blogRef = $this->db->collection('blogPost')->document($id);
        return $blogRef->delete();
    }
}