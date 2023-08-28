<?php
 require '../methods/login_method.php';
 $user_id = $_SESSION["user_id"];
 $spotify_id = $_POST['spotify_id'];
 $communitie_id = $_SESSION["comm_id"];
 $score = $_POST['eva'];
 $comment = $_POST['review'];
 if(login_check()){
    $db_ins = new DataIns();
    $db_ins->track_eva($user_id, $spotify_id, $communitie_id, $score, $comment);
    header("Location:../views/detail_song.php?spotify_id=$spotify_id");
 }else{
    header("Location:../views/login_form.html");
 }
?>