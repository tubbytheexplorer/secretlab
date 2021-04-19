<?php 
include_once "class/AppKeyClass.php";

$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method == "GET") {
    
    $app_key = "";
    $timestamp = "";
    $data = array();

    // get passed data
    if (isset($_GET['app_keys'] )) {
       $app_key = $_GET['app_keys']; 
    }

    if (isset($_GET['timestamp'] )) {
       $timestamp = $_GET['timestamp']; 
    }

    if (isset($_GET['get_all_records'] )) {
        //retrieve all app keys
        $Keys = new AppKeys();
        $data = $Keys->GetAllAppKeys();
    } else {
        // make sure app key is not empty
        if (!empty(trim($app_key))) {
            //retrieve app key
            $Keys = new AppKeys();
            $data = $Keys->GetAppKey($app_key, $timestamp);
        }
        else{
            $data['status'] = 400;
            $data['message'] = 'Key cannot be found';
        }
    }
    echo json_encode($data, JSON_PRETTY_PRINT);
    
} else if ($request_method == "POST") {
    // get posted data
    $json_data = json_decode(file_get_contents('php://input'), true);

    //insert or update app key
    $Keys = new AppKeys();
    $data = $Keys->InsertAppKeys($json_data);
    
    echo json_encode($data, JSON_PRETTY_PRINT);
}
?>