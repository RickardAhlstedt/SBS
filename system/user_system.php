<?php

/* Purpose of file:
 * Define a basic user-system that can be called via AJAX.
 * 
 * When posting to this file, data should be formatted like so:
 * function=wantedFunction&params=param1;param2;param3
 */

require 'config/user_config.php';
require 'system_utils.php';

global $func, $params;

define('isFuncSet', isset($_POST['function']));
define('isParamsSet', isset($_POST['params']));

if(IS_AJAX){
    if(isFuncSet && isParamsSet){
        $func = $_POST['function'];
        $params = explode(";", $_POST['params']);
        switchFunc();
    }else{
        die("No function and/or parameters passed.");
    }
}

/* Add functions after switchFunc (mainly for readability), after that,
 * simply add your new function to switchFunc, easy as 3.14159265
 */

function switchFunc(){
    global $func, $params;
    switch($func){
        case 'loginUser':
            loginUser($params[0], $params[1]);
            break;
        case 'userExists':
            checkUserExists($params[0]);
            break;
        case 'emailExists':
            checkEmailExists($params[0]);
            break;
        case 'createUser':
            createUser();
            break;
        case 'getUserID':
            getUserID($params[0]);
            break;
        case 'getUsername':
            getUsername($params[0]);
            break;
        case 'checkSession':
            checkSession();
            break;
        default:
            echo "There was an error.";
            writeDevLog('info', "Function (" . $func.") was called but doesn't exists, additionally, these parameters were sent". print_r($params). ".");
            break;
    }
}

function checkSession(){
    if(isset($_SESSION[sessionName]))
        echo "1";
    else
        echo "0";
}

function loginUser($username, $password){
    openConnection();
    $username = stripcslashes(mysql_real_escape_string($username));
    $password = stripcslashes(mysql_real_escape_string($password));
    $password = md5($password . salt);
    $sql = "SELECT * FROM `". UserTable ."`WHERE `username`='$username' and `password='`$password'";
    $result = mysql_query($sql);
    $rowCount = mysql_num_rows($result);
    
    if($rowCount == 1){
        // User logged in without errors, let's register the session and return '1'.
        session_register(sessionName);
        echo "1";
    }else{
        //Wrong credentials or user doesn't exist.
        echo "0";
    }
    closeConnection();
}

?>
