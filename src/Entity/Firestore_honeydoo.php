<?php

namespace App\Model;

use Google\Cloud\Firestore\FirestoreClient;

class Firestore_honeydoo
{
    protected $db;
    public function __construct()
    {
        $this->db = new FirestoreClient([
            'keyFilePath' => $_SERVER['DOCUMENT_ROOT'] . '/hondeydoo-19eb1_credentials.json',
            'projectId' => 'hondeydoo-19eb1'
        ]);
    }

    public function fetchUser($email, $userPassword)
    {
        /*$query = $this->db->collection('realtor')->where('password', '=', $password)->where('email', '=', $email);*/
        $query = $this->db->collection('realtor')->where('email', '=', $email);
        $documents = $query->documents();

        foreach ($documents as $document) {
            if ($document->exists()) {
                if (password_verify($userPassword, $document->data()["password"]))
                {
                    return $document->data();
                }
            } else {
                return false;
            }
        }
    }

    public function fetchUserByEmail($email)
    {
        $query = $this->db->collection('realtor')->where('email', '=', $email);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $document->data();
            } else {
                return false;
            }
        }
    }

    public function fetchRealtorById($id)
    {
        /*$query = $this->db->collection('realtor')->where('realtor_id', '=', $id);*/

        /*return $query->snapshot();*/
        $query = $this->db->collection('realtor')->where('realtor_id', '=', $id);
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

    public function fetchUserClients($userId): array
    {
        $res = [];
        $query = $this->db->collection('realtor_clients')->where('realtor_id', '=', $userId);
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

    public function fetchAllMobileAppClients(): array
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

    public function createNewClientCollection($data)
    {
        return $this->db->collection('realtor_clients')->add($data);
    }

    public function fetchClientCollectionById($docId)
    {
        $query = $this->db->collection('realtor_clients')->document($docId);
        return $query->snapshot();
    }
    public function updateClientCollection($clientCollectionId, $data)
    {
        $clientCollectionRef = $this->db->collection('realtor_clients')->document($clientCollectionId);
        return $clientCollectionRef->update($data);
    }

    public function deleteClientCollection($clientCollectionId)
    {
        $clientCollectionRef = $this->db->collection('realtor_clients')->document($clientCollectionId);
        return $clientCollectionRef->delete();
    }

    // hd_pros

    public function fetchHdPros($userId)
    {
        $res = [];
        $query = $this->db->collection('hd_pros')->where('realtor_id', '=', $userId);
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

    public function fetchHdProsById($docId)
    {
        $query = $this->db->collection('hd_pros')->document($docId);
        return $query->snapshot();
    }

    public function createNewHdPros($data)
    {
        return $this->db->collection('hd_pros')->add($data);
    }

    public function updateHdPros($hdProsId, $data)
    {
        $proServiceRef = $this->db->collection('hd_pros')->document($hdProsId);
        return $proServiceRef->update($data);
    }

    public function deleteHdPros($hdProsId)
    {
        $blogRef = $this->db->collection('hd_pros')->document($hdProsId);
        return $blogRef->delete();
    }

    public function fetchUsers()
    {
        $res = [];
        $query = $this->db->collection('realtor')->where('role', '=', "ROLE_USER");
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

    public function fetchEmailContent()
    {
        $query = $this->db->collection('invitation_email')->document('invitation_email');
        return $query->snapshot();
    }


    public function updateEmailContent($data)
    {
        $emailRef = $this->db->collection('invitation_email')->document('invitation_email');
        return $emailRef->update($data);
    }

    public function updateRealtorInfo($realtorId, $data)
    {
        $realtorRef = $this->db->collection('realtor')->document($realtorId);
        return $realtorRef->update($data);
    }

    public function checkIfRealtorUserExists($email)
    {
        $query = $this->db->collection('realtor')->where('email', '=', $email);
        return !$query->documents()->isEmpty();
    }

    public function createNewRealtorUser($data)
    {
        $docRef = $this->db->collection('realtor')->add($data);
        return $docRef->id();
    }
    public function setRealtorId($realtorId)
    {
        $realtorRef = $this->db->collection('realtor')->document($realtorId);
        return $realtorRef->update([['path' => 'realtor_id', 'value' => $realtorId]]);
    }

    public function saveResetPasswordToken($data)
    {
        return $this->db->collection('reset_password_request')->add($data);
    }

    public function deleteResetRequest($token)
    {
        $query = $this->db->collection('reset_password_request')->where('token', '=', $token);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $this->db->collection('reset_password_request')->document($document->id())->delete();
            } else {
                return false;
            }
        }
    }

    public function fetchResetEmail()
    {
        $query = $this->db->collection('reset_password_email')->document('reset_password_email');
        return $query->snapshot();
    }

    public function fetchTokenFromDb($token)
    {
        $query = $this->db->collection('reset_password_request')->where('token', '=', $token);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $document->data();
            } else {
                return false;
            }
        }
    }

    public function updateResetEmail($data)
    {
        $emailRef = $this->db->collection('reset_password_email')->document('reset_password_email');
        return $emailRef->update($data);
    }
}