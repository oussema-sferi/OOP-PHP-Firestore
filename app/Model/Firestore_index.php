<?php

namespace App\Model;

use Google\Cloud\Firestore\FirestoreClient;

class Firestore_index
{
    protected $db;
    public function __construct()
    {
        $this->db = new FirestoreClient([
            'keyFilePath' => 'hondeydoo-19eb1_credentials.json',
            'projectId' => 'hondeydoo-19eb1'
        ]);
    }
    public function getDocument()
    {
        $res = [];
        $query = $this->db->collection('user')->documents()->rows();
        if(!empty($query)) {
            foreach ($query as $item) {
                $res[] = $item->data();
            }
        }
        return $res;
    }

}