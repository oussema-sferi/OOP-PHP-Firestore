<?php

namespace App\Model;

use Google\Cloud\Firestore\FirestoreClient;

class Firestore_honeydoo
{
    protected $db;
    public function __construct()
    {
        $this->db = new FirestoreClient([
            'keyFilePath' => '../../hondeydoo-19eb1_credentials.json',
            'projectId' => 'hondeydoo-19eb1'
        ]);
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

    public function createNewBlogPost($data)
    {
        return $this->db->collection('blogPost')->add($data);
    }

    public function updateBlogPost($blogId, $data)
    {
        $blogRef = $this->db->collection('blogPost')->document($blogId);
        return $blogRef->update($data);
    }

    public function deleteBlogPost($blogId)
    {
        $blogRef = $this->db->collection('blogPost')->document($blogId);
        return $blogRef->delete();
    }

    public function fetchBlogById($docId)
    {
        $query = $this->db->collection('blogPost')->document($docId);
        return $query->snapshot();
    }

    public function fetchProServices($userId)
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

    public function fetchProServiceById($docId)
    {
        $query = $this->db->collection('realtor_home_pro_service')->document($docId);
        return $query->snapshot();
    }

    public function createNewProService($data)
    {
        return $this->db->collection('realtor_home_pro_service')->add($data);
    }

    public function updateProService($proServiceId, $data)
    {
        $proServiceRef = $this->db->collection('realtor_home_pro_service')->document($proServiceId);
        return $proServiceRef->update($data);
    }

    public function deleteProService($proServiceId)
    {
        $blogRef = $this->db->collection('realtor_home_pro_service')->document($proServiceId);
        return $blogRef->delete();
    }
}