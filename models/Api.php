<?php

namespace app\models;

use Yii;
use yii\base\Model;

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
        $url = self::URL_BASE."aws/files";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output,true);
    }

    public function validateFile($key){
        $url = self::URL_BASE."aws/files/{$key}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output,true);
    }

    public function signUp($model){
        $data = array("file-name"=>$model['file']->name,"file-type"=>$model['file']->type);
        
        $url = sprintf("%s?%s", self::URL_BASE."aws/sign", http_build_query($data));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output,true);
    }

    public function uploadFile($model,$url){
    $url = $url;
    $header = array('Content-Type: application/x-www-form-urlencoded');
    $fields = array('file'=>'@'.$model->tempName.'/'.$model->name);
    
    $resource = curl_init();
    curl_setopt($resource, CURLOPT_URL, $url);
    curl_setopt($resource, CURLOPT_HTTPHEADER, $header);
    curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($resource, CURLOPT_POST, 1);
    curl_setopt($resource, CURLOPT_CUSTOMREQUEST,  'PUT');
    curl_setopt($resource, CURLOPT_POSTFIELDS, $fields);
    $result = json_decode(curl_exec($resource));
    curl_close($resource);
    
    return true;
    }

}
