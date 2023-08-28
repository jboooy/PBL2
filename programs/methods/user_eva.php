<?php
 require '../methods/login_method.php';
 $user_id = $_SESSION["user_id"];
 $subject_user_id = $_POST['sub_user'];
 $communitie_id = $_SESSION["comm_id"];
 $rate = $_POST['rate'];
 $comment = $_POST['comment'];
 if(login_check()){
    $db_ins = new DataIns();
    $db_ins->user_eva($user_id, $subject_user_id, $communitie_id, $rate*20, $comment);
    header("Location:../views/profile.php?user_id=$subject_user_id");
 }else{
    header("Location:../views/login_form.html");
 }
?>