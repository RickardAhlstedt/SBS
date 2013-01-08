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

function checkEmailExists($email){
    openConnection();
    $email = stripcslashes(mysql_real_escape_string($email));
    $sql = mysql_query("SELECT * FROM `".UserTable."` WHERE `mail`='$email'");
    if(!$sql){
        writeDevLog("severe", mysql_error());
        die("An error has occured, sorry about that.");
    }
    $count = mysql_num_rows($sql);
    if($count == 1)
        echo "0";
    else
        echo "1";
    closeConnection();
}

function checkUserExists($username){
    openConnection();
    $username = stripcslashes(mysql_real_escape_string($username));
    $sql = mysql_query("SELECT * FROM `".UserTable."` WHERE `username`='$username'");
    if(!$sql){
        writeDevLog("severe", mysql_error());
        die("An error has occured, sorry about that.");
    }
    $count = mysql_num_rows($sql);
    if($count == 1)
        echo "0";
    else
        echo "1";
    closeConnection();
}

function getUserID($username){
    $IDQuery = mysql_query("SELECT `ID` from `". UserTable ."` WHERE `username` = '$user'");
    if(!$IDQuery){
        echo "An error has occured, sorry about that.";
        writeDevLog("severe", mysql_error());
    }
    $ID = mysql_fetch_row($IDQuery);
    echo $ID[0];
}

function getUsername($ID){
    $NameQuery = mysql_query("SELECT `username` from `". UserTable ."` where `ID` = '$ID'");
    if(!$NameQuery){
        echo "An error has occured, sorry about that.";
        writeDevLog("severe", mysql_error());
    }
    $Name = mysql_fetch_row($NameQuery);
    echo $Name[0];
}

function createUser($username, $password, $mail){
    $username = stripcslashes(mysql_real_escape_string($username));
    $password = stripcslashes(mysql_real_escape_string($password));
    $password = md5($password . salt);
    $mail = stripcslashes(mysql_real_escape_string($mail));
    //If you want to implement date of register, I've already gone ahead and fixed the date-codex for you =)
    $date = date("Y-m-d H:i:s");
    if(!checkUserExists($username) || !checkEmailExists($mail)){
        openConnection();
        $insertQuery = "INSERT INTO `". UserTable ."` VALUES('', '$username', '$password', '$mail', '$date');";
        $result = mysql_query($insertQuery);
        if(!$result){
            die("There was an error trying to register, please try again later");
            writeDevLog("severe", mysql_error());
        }else{
            echo "Your account was successfully created!";
            //Send the user a email or whatnot here.
            closeConnection();
        }
    }else{
        echo "Duplcate account detected. Account wasn't registered.";
    }
}

?>
