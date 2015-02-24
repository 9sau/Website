<?php

$con = new mysqli("localhost","root","","friends");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 

?>