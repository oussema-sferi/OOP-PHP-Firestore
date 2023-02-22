<?php

namespace App;
use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Firestore\FirestoreClient;

class DBConfig
{
    /*protected $db;*/
    private const KEY_FILE_PATH = __DIR__ . '/hondeydoo-19eb1_credentials.json';
    private const PROJECT_ID = 'hondeydoo-19eb1';
    /*public function __construct()
    {
        $this->db = new FirestoreClient([
            'keyFilePath' => $_SERVER['DOCUMENT_ROOT'] . '/hondeydoo-19eb1_credentials.json',
            'projectId' => 'hondeydoo-19eb1'
        ]);
    }*/

    public static function getDbConnection()
    {
        /*$conn = new FirestoreClient([
            'keyFilePath' => $_SERVER['DOCUMENT_ROOT'] . '/hondeydoo-19eb1_credentials.json',
            'projectId' => 'hondeydoo-19eb1'
        ]);*/
        //Set the PDO error mode to exception
        try {
            return new FirestoreClient([
                'keyFilePath' => $_SERVER['DOCUMENT_ROOT'] . '/hondeydoo-19eb1_credentials.json',
                'projectId' => 'hondeydoo-19eb1'
            ]);
        } catch (GoogleException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}