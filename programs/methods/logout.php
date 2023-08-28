<?php
error_reporting(0);

session_start();
session_destroy();
header('location:../views/home.php');

/*
if($_SESSION["user_id"]==NULL){
    print "ログアウト成功" . "<BR>";
    echo '<a href ="login_test.php"><button>ログインテストをする</button></a>';
}
else{
    print "ログアウト失敗";
}
*/
?>