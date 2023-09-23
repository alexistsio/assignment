<?php

include ('init.php');


include (DIR_SYS . 'loader.php');
include (DIR_SYS . 'controller.php');
include (DIR_SYS . 'model.php');
include (DIR_SYS . 'registry.php');

//Registry
$Registry = new Registry();

//Loader
$Loader = new Loader();

//Database
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

//Assign Objects to Registry
$Registry->Set('loader',$Loader);
$Registry->Set('conn',$conn);

$Loader->model('user');
$User = new User($Registry);
$Registry->Set('user',$User);

$headers = apache_request_headers();

$owner_id = false;
if (isset($headers['Authorization'])){
    if (strpos($headers['Authorization'],'Bearer ') === 0){
        $access_token = str_replace('Bearer ','',$headers['Authorization']);
        $validation = $User->validateAccessToken($access_token);
        if ($validation['status']){
            $owner_id = $validation['owner_id'];
        }
    }
}
$Registry->Set('owner_id',$owner_id);

$request_data = file_get_contents('php://input');
$request_data = json_decode($request_data,true);
$input_check = json_last_error();
if ($input_check){
    $request_data = [];
}

if (isset($_GET['route'])){
    $path = explode('/',$_GET['route']);
    $Registry->Set('request_data',$request_data);
    $response = $Loader->Controller($path,$Registry);
//    if (!$input_check){
//        $path = explode('/',$_GET['route']);
//        $Registry->Set('request_data',$request_data);
//        $response = $Loader->Controller($path,$Registry);
//    }else{
//        $response = ['code' => '400', 'data' => ['message' => 'Invalid data type']];
//    }
}else{
    $response = ['code' => '404', 'data' => ['message' => 'Resource not found']];
}

header('Content-Type: application/json; charset=utf-8');
http_response_code($response['code']);
echo json_encode($response['data']);

// Output







