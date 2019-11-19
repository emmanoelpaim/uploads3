<?php

namespace app\models;

use Yii;
use yii\base\Model;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\ObjectUploader;
use Aws\S3\MultipartUploader;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class Api extends Model
{
    const URL_BASE = 'http://localhost:5000/';

    public function listFiles(){
        $bucketName = 'uploadkim';
        $IAM_KEY = 'AKIAQCQIC6L5STJXXN52' ;
        $IAM_SECRET = '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';

        $s3Client = new S3Client([
            'region' => 'sa-east-1',
            'version' => '2006-03-01',
            'scheme'  => 'http',
            'credentials' => array(
                'key' => $IAM_KEY,
                'secret' => $IAM_SECRET
            ),
        ]);


        // Use the plain API (returns ONLY up to 1000 of your objects).
        try {
            $objects = $s3Client->listObjects([
                        'Bucket' => $bucketName, // REQUIRED
                        'Delimiter' => '/'
                    ]);

            return $objects;
        } catch (S3Exception $e) {
            return $e->getMessage() . PHP_EOL;
        }

    }

    public function validateFile($key){
        $bucketName = 'uploadkim';
        $IAM_KEY = 'AKIAQCQIC6L5STJXXN52' ;
        $IAM_SECRET = '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';

        $s3Client = new S3Client([
            'region' => 'sa-east-1',
            'version' => '2006-03-01',
            'scheme'  => 'http',
            'credentials' => array(
                'key' => $IAM_KEY,
                'secret' => $IAM_SECRET
            ),
        ]);

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key' => $key
        ]);

        try {
            $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
            $presignedUrl = (string)$request->getUri();
            return $presignedUrl;
        } catch (MultipartUploadException $e) {
            echo $e->getMessage() . "\n";
        }
    }

    public function uploadFile(){

        $bucketName = 'uploadkim';
        $IAM_KEY = 'AKIAQCQIC6L5STJXXN52' ;
        $IAM_SECRET = '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';

        $s3Client = new S3Client([
            'region' => 'sa-east-1',
            'version' => '2006-03-01',
            'scheme'  => 'http',
            'credentials' => array(
                'key' => $IAM_KEY,
                'secret' => $IAM_SECRET
            ),
        ]);

        // Use multipart upload
        $source = $_FILES["LoginForm"]["tmp_name"]["file"];
        $uploader = new MultipartUploader($s3Client, $source, [
            'bucket' => $bucketName,
            'key' => $_FILES["LoginForm"]["name"]["file"],
        ]);

        try {
            $result = $uploader->upload();
            echo "Upload complete: {$result['ObjectURL']}\n";
        } catch (MultipartUploadException $e) {
            echo $e->getMessage() . "\n";
        }
    }

    public function deleteFile($key){
        $bucketName = 'uploadkim';
        $IAM_KEY = 'AKIAQCQIC6L5STJXXN52' ;
        $IAM_SECRET = '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';

        $s3Client = new S3Client([
            'region' => 'sa-east-1',
            'version' => '2006-03-01',
            'scheme'  => 'http',
            'credentials' => array(
                'key' => $IAM_KEY,
                'secret' => $IAM_SECRET
            ),
        ]);

        try {
            // Delete an object from the bucket.
            $s3Client->deleteObject([
                'Bucket' => $bucketName,
                'Key'    => $key
            ]);
            return true;
        } catch (MultipartUploadException $e) {
            echo $e->getMessage() . "\n";
        }
    }

}
