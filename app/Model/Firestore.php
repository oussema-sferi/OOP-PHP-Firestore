<?php

namespace App\Model;

use Google\Cloud\Firestore\FirestoreClient;

class Firestore
{
    private FirestoreClient $firestore;
    public function __construct()
    {
        $this->firestore = new FirestoreClient([
            /*"keyFilePath" => "honeydoo_credentials.json",
            'projectId' => 'hondeydoo-19eb1'*/
            /*"keyFilePath" => "honeydoo_credentials.json",*/
            'projectId' => 'first-project-ba463'
        ]);
    }
    public function test()
    {
        return $this->firestore->collection("countries")->documents()->rows();
    }

}