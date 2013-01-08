<?php

/* Purpose of file:
 * Provide some backbone-features.
 */

require 'config/user_config.php';

function getWebProp($prop){
    $propArray = parse_ini_file("config/web-config.ini");
    return $propArray[$prop];
}

function getSystemProp($prop){
    $propArray = parse_ini_file("config/system.ini");
    return $propArray[$prop];
}

function isDebugMode(){
    $debug = getSystemProp('debug');
    return $debug;
}

function getExtension($filename){
    $path_info = pathinfo($filename);
    return $path_info['extension'];
}

function writeDevLog($level, $msg){
    $logPath = getSystemProp('dev-logs-path');
    $loggingEnabled = getSystemProp('dev-logs');
    $level = strtolower($level);
    $date = date("D/F-Y | g:i A");
    if($loggingEnabled){
        if($level == 'info' || $level == 'severe' || $level == 'warning'){
            $fp = fopen($logPath . "DevLog.html", 'a') or die("Sorry, couldn't open log-file..");
            fwrite($fp, "\r\n".'<span class="'. $level .'">[ '. strtoupper($level) . ' ][ '. $date .' ] '. $msg .'</span><br /><br /><br />');
            fclose($fp);
        }else{
            echo "Log-level doesn't exist.<br />";
            return;
        }
    }else{
        echo "Logging is not enabled.";
        return;
    }
}

function escapeString($stringtoEscape){
    return stripcslashes(mysql_real_escape_string($stringtoEscape));
}

function openConnection(){
    mysql_connect(dbHost, dbUsername. dbPassword) or die("Couldn't connect");
    mysql_select_db(db)or die("Cannot select DB");
}

function closeConnection(){
    mysql_close();
}

?>
