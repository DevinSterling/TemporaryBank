<?php
require_once('../../../../../../../private/config.php');
require_once('../../../../../../../private/userbase.php');
require_once('../../../../../../../private/functions.php');

forceHTTPS(); // Force https connection
session_start(); // Start Session
checkEmployeeStatus(); // Check if the employee is signed in

session_start();

/* SESSION Variables */
$key = $_SESSION['key'];

/* POST Variables */
$clientID = $_POST['id'];
$token = $_POST['token'];

/* Object variables */
$dbResponse = false;
$dbMessage = 'Failed to approve client registration request';

/* Calculate expected token */
$calc = hash_hmac('sha256', '/approveRegistration.php', $key);

/* Confirm token and user input */
if (hash_equals($calc, $token)
    && !empty($clientID)) {
    
    /* Input Validation */
    $isMatch = is_numeric($clientID);
    
    if ($isMatch) {
        /* Get database connection */
        $db = getUpdateConnection();
         
        /* Check connection */
        if ($db !== null) {
            $dbResponse = true;
            $dbMessage = "Fetch API Success!";
            
            $db->close();
        }
    }
}

$object = (object)array();
$object->response = $dbResponse;
$object->message = $dbMessage;

$json = json_encode($object);

echo $json;
