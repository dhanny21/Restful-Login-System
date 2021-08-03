<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'Restful_login';

$con = new mysqli($host, $user, $pass, $db);

if($con->connect_error){
    http_response_code(400);
    header('content-type: text/plain');
    echo "could not connect" . $con->connect_error;
    exit();
}
?>