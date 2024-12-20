<?php 
@session_start();

$servername = "localhost";
$username = "legumkart_legal";
$password = "l2t{D%3N.lPI";
$dbname = "legumkart_legal";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

error_reporting(0);
date_default_timezone_set("Asia/Calcutta");  

function callAPI($method, $url, $data){
     $curl = curl_init();

     switch ($method){
        case "POST":
           curl_setopt($curl, CURLOPT_POST, 1);
           if ($data)
              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
           break;
        case "PUT":
           curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
           if ($data)
              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                       
           break;
        default:
           if ($data)
              $url = sprintf("%s?%s", $url, http_build_query($data));
     }

     // OPTIONS:
     curl_setopt($curl, CURLOPT_URL, $url);
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'APIKEY: 111111111111111111111',
        'Content-Type: application/json',
     ));
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

     // EXECUTE:
     $result = curl_exec($curl);
     if(!$result){die("Connection Failure");}
     curl_close($curl);
     return json_decode($result, true);;
  }


?>