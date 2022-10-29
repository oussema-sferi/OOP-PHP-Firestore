<?php

namespace App\Model;

use Google\Cloud\Firestore\FirestoreClient;

class Firestore_honeydoo
{
    protected $db;
    protected $name;
    public function __construct($collection)
    {
        $this->db = new FirestoreClient([
            'keyFilePath' => '../../hondeydoo-19eb1_credentials.json',
            'projectId' => 'hondeydoo-19eb1'
        ]);

        $this->name = $collection;
    }

    /*public function getDocument()
    {
        $res = [];
        $query = $this->db->collection($this->name)->documents()->rows();
        if(!empty($query)) {
            foreach ($query as $item) {
                $res[] = $item->data();
            }
        }
        return $res;
    }*/
    public function getDocument()
    {
        $res = [];
        $query = $this->db->collection('realtor')->documents()->rows();
        if(!empty($query)) {
            foreach ($query as $item) {
                $res[] = $item->data();
            }
        }
        return $res;
        /*$query = $this->db->collection('realtor')->where('portalp', '=', 'testpass77');
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                printf('Document data for document %s:' . PHP_EOL, $document->id());
                print_r($document->data());
                printf(PHP_EOL);
            } else {
                printf('Document %s does not exist!' . PHP_EOL, $document->id());
            }
        }*/
       /* if(!empty($query)) {
            foreach ($query as $item) {
                $res[] = $item->data();
            }
        }
        return $res;*/
    }

    public function fetchUser($email, $password)
    {
        $query = $this->db->collection('realtor')->where('portalp', '=', $password)->where('email', '=', $email);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $document->data();
            } else {
                return false;
            }
        }
    }

    public function fetchBlogPosts($userId)
    {
        $res = [];
        $query = $this->db->collection('blogPost')->where('realtor_id', '=', $userId);
        $documents = $query->documents();
        /*print_r($documents);
        die();*/
        foreach ($documents as $document) {
            if ($document->exists()) {

                $res[] = $document->data();
            }
        }
        /*print_r($res);
        die();*/
        return $res;
    }
}