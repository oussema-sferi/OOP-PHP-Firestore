<?php

namespace App\Entity;


use App\DBConfig;

class ProService
{
    protected $db;
    public function __construct()
    {
        $this->db = DBConfig::getDbConnection();
    }

    public function findAllByUser($userId)
    {
        $res = [];
        $query = $this->db->collection('realtor_home_pro_service')->where('realtor_id', '=', $userId);
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
        return $this->db->collection('realtor_home_pro_service')->add($data);
    }

}