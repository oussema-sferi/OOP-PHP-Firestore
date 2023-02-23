<?php

namespace App;
use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Firestore\FirestoreClient;

class DBConfig
{
    private const KEY_FILE_PATH = __DIR__ . '/../db_config.json';
    private const PROJECT_ID = 'hondeydoo-19eb1';
    public static function getDbConnection()
    {
        try {
            return new FirestoreClient([
                'keyFilePath' => self::KEY_FILE_PATH,
                'projectId' => self::PROJECT_ID
            ]);
        } catch (GoogleException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}