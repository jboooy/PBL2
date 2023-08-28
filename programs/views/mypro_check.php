<?php
require '../methods/login_method.php';
if(login_check()){
    $user_id=$_SESSION["user_id"];
    header("Location:../views/profile.php?user_id=$user_id");
}else{
    header("Location:../views/login_form.html");
}



?>
