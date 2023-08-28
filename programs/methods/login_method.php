<?php
require_once 'db_m.php';
session_start();
function login_check(){
    $db_read = new GetUeserAndComData;

    if($_SESSION['user_id'] == NULL){
        return false;
    }
    else{
        $res = $db_read->get_all_users();
        foreach($res as $x){
            if($_SESSION['user_id'] == $x['user_id']){
                return true;
            }
        }
        return false;
    }
}
?>