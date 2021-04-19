<?php
include_once "database.php";

class AppKeys {

  function __construct() 
  {

  }

  function GetAppKey($app_key, $timestamp)
  {
    $db_connect = new DbConnect();
    $conn = $db_connect->OpenConn();
    $data = array();

    if (!empty(trim($timestamp))) {
      $get_keys = "SELECT * FROM apps WHERE app_key = '$app_key' AND timestamp_updated = '$timestamp'";
      $get_keys_result = $conn->query($get_keys) or die ("Error: ".$conn->error);
    } else {
      $get_keys = "SELECT * FROM apps WHERE app_key = '$app_key'";
      $get_keys_result = $conn->query($get_keys) or die ("Error: ".$conn->error);
    }

    if ($get_keys_result->num_rows > 0) {
      $list_keys = array();
      foreach ($get_keys_result as $key => $gkr) {
        $list_keys["app_key"] = $gkr['app_key'];
        $list_keys["value"] = $gkr['value'];
        $list_keys["timestamp_created"] =  gmdate("Y-m-d H:i:s", $gkr['timestamp_created']);
        $list_keys["timestamp_updated"] =  gmdate("Y-m-d H:i:s", $gkr['timestamp_updated']);
      }

      if (!empty(trim($timestamp))) {
        $data_key = $list_keys["app_key"];
      } else {
        $data_key = $list_keys["value"];
      }

      $data['status'] = 200;
      $data['message'] = 'success';
      $data['result'] = $data_key;
    } else {
      $data['status'] = 400;
      $data['message'] = 'No records available';
    }
   
    $db_connect->CloseConn($conn);
    return $data;
  }

  function GetAllAppKeys()
  {
    $db_connect = new DbConnect();
    $conn = $db_connect->OpenConn();
    $data = array();
    
    $get_all_keys = "SELECT * FROM apps";
    $get_all_keys_result = $conn->query($get_all_keys) or die ("Error: ".$conn->error);

    if ($get_all_keys_result->num_rows > 0) {
      $list_keys = array();
      foreach ($get_all_keys_result as $key => $gakr) {
        $list_keys[$key]["app_key"] = $gakr['app_key'];
        $list_keys[$key]["value"] = $gakr['value'];
        $list_keys[$key]["timestamp_created"] =  gmdate("Y-m-d H:i:s", $gakr['timestamp_created']);
        $list_keys[$key]["timestamp_updated"] =  gmdate("Y-m-d H:i:s", $gakr['timestamp_updated']);
      }
      
      $data['status'] = 200;
      $data['message'] = 'success';
      $data['result'] = $list_keys;
    } else {
      $data['status'] = 400;
      $data['message'] = 'No records available';
    }
   
    $db_connect->CloseConn($conn);
    return $data;
  }

  function InsertAppKeys($key_data)
  {
    $db_connect = new DbConnect();
    $conn = $db_connect->OpenConn();
    date_default_timezone_set('UTC');

    $data = array();
    
    if (isset($key_data['app_keys']) && !empty(trim($key_data['app_keys']))) {
      $app_key = isset($key_data['app_keys']) ? $key_data['app_keys'] : "";
      $value = isset($key_data['value']) ? $key_data['value'] : "";
      $current_date = date("Y-m-d H:i:s");
      $timestamp_created = strtotime($current_date);
      $timestamp_updated = strtotime($current_date);
    

      $verify_key = "SELECT * FROM apps WHERE app_key = '$app_key'";
      $verify_key_result = $conn->query($verify_key) or die ("Error: ".$conn->error);

      if ($verify_key_result->num_rows > 0) {
        $update_app_key = "UPDATE apps SET value = '$value', timestamp_updated = '$timestamp_updated'  WHERE app_key = '$app_key'";
        $update_app_keys_result = $conn->query($update_app_key) or die ("Error: ".$conn->error);

        $get_updated_key = "SELECT * FROM apps WHERE app_key = '$app_key'";
        $get_updated_key_result = $conn->query($get_updated_key) or die ("Error: ".$conn->error);

        $updated_key = array();
        foreach ($get_updated_key_result as $key => $guk) {
          $updated_key[$key]["app_key"] = $guk['app_key'];
          $updated_key[$key]["value"] = $guk['value'];
          $updated_key[$key]["timestamp_created"] = gmdate("Y-m-d H:i:s", $guk['timestamp_created']);
          $updated_key[$key]["timestamp_updated"] = gmdate("Y-m-d H:i:s", $guk['timestamp_updated']);
        }

        $data['status'] = 200;
        $data['message'] = 'App key was successfully updated.';
        $data['result'] = $updated_key;
      } else {

        $insert_app_keys = "INSERT INTO apps (app_key, value, timestamp_created, timestamp_updated) 
                VALUES ('$app_key', '$value', '$timestamp_created', '$timestamp_updated')";
        $insert_app_keys_result = $conn->query($insert_app_keys) or die ("Error: ".$conn->error);
        $last_inserted_id = $conn->insert_id;
        
        $get_last_inserted_key = "SELECT * FROM apps WHERE id = $last_inserted_id";
        $get_last_inserted_key_result = $conn->query($get_last_inserted_key) or die ("Error: ".$conn->error);

        

        if (!$insert_app_keys_result) {
          $data = array('result' => 400, 'message' => 'App key was not successfully created. Please try again.');
        } else {
          $last_key = array();
          foreach ($get_last_inserted_key_result as $key => $rgk) {
            $last_key[$key]["app_key"] = $rgk['app_key'];
            $last_key[$key]["value"] = $rgk['value'];
            $last_key[$key]["timestamp_created"] = gmdate("Y-m-d H:i:s", $rgk['timestamp_created']);
            $last_key[$key]["timestamp_updated"] = gmdate("Y-m-d H:i:s", $rgk['timestamp_updated']);
          }

          $data['status'] = 200;
          $data['message'] = 'App key was successfully created.';
          $data['result'] = $last_key;
        }
      }
      $db_connect->CloseConn($conn);
    } else {
      $data = array('result' => 400, 'message' => 'App key cannot be found. Please check the data you posted and try again.'); 
    }

    return $data;
  }

}   
?>
