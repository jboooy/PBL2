<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<title>ホーム画面</title>
<link rel="stylesheet" href="./detail_css/main_tag.css">
<link rel="stylesheet" href="./detail_css/border.css">
<link rel="stylesheet" href="./detail_css/haikei.css">

<?php
 session_start();
 require '../methods/api_request.php';
 require '../methods/db_m.php';
 require '../methods/sortByKey.php';
?>

<style>
  .box{
    text-align: center;
  }
  .box p{
    display: inline-block;
    text-align: left;
  }
</style>

</head>

<body class="haikei">

  <nav>
    <ul>
      <li><a class=”current” href=home.php> Home </a></li>
      <li><a href=search.html> Search </a></li>
      <li><a href=communitie.php> Community </a></li>
      <li><a href=mypro_check.php> Profile </a></li>
    </ul>
  </nav>

  <?php
    //セッションが空なら1を入れる
    if(!isset($_SESSION['comm_id'])){
      $_SESSION['comm_id'] = 1;
    }

    //DBインスタンス生成
    $db_review = new GetReview();
    //曲のIDと平均点取得
    $track_score = $db_review->get_score( true, $_SESSION['comm_id'] );
    //ユーザーのIDと平均点取得
    $user_score = $db_review->get_score( false, $_SESSION['comm_id'] );
  ?>

    <div class="border" style="text-align: center; background-color:white;">
    <h2>ランキング</h2>

    <hr>

      <h3>楽曲ランキング</h3>

        <div id="track_ranking">
          <?php
            //データベースに情報がない場合
            if(empty($track_score)){
              echo "曲のレビューを投稿してみましょう！<br>";
            }else{
              //曲ランキングソート
              $track_id_rank = sortByKey( 'AVG(score)', SORT_DESC, $track_score );

              //ソート結果を順番に表示するコード
              foreach( $track_id_rank as $key => $value ){
                if( $key >= 3 ) break; //3個目まで表示して終了
                //URLとIDを結合
                $id_tmp = $value['spotify_id'];
                $spotify_url = "https://open.spotify.com/embed/track/"."$id_tmp";

                //ランキング配列内の曲情報取得
                $track_info = get_track_info($track_id_rank[$key]['spotify_id']);

                //曲ランキング表示
                echo "<font size=\"+1\">";
                echo '<b>' . $key+1 . '.</b> ';
                echo "<a href=\"./detail_song.php?spotify_id=".$id_tmp."\">".$track_info->tracks[0]->album->name."</a>";
                echo "</font>";
                echo '  , <b>'. $value['AVG(score)'] . '</b> 点<br>';

                //spotifyの枠で表示
                echo "<iframe src=".$spotify_url."
                width=\"60%\"
                height=\"123\"
                frameborder=\"0\"
                allowtransparency=\"true\"
                allow=\"encrypted-media\"></iframe><br>";
              }
            }
          ?>
        </div>

      <h3>ユーザーランキング</h3>

        <div id="user_ranking">
          <?php
          //データベースに情報がない場合
          if(empty($user_score)){
            echo "ユーザーのレビューを投稿してみましょう！<br>";
          }else{
            //ユーザーランキングソート
            $user_id_rank = sortByKey('AVG(score)', SORT_DESC, $user_score );

            //ソート結果を順番に表示するコード
            foreach( $user_id_rank as $key => $value ){
              if( $key >= 3 ) break; //3個目まで表示して終了

              echo "<div class=\"box\">";
              echo "<p>";

              //ユーザーランキング表示
              echo "<font size=\"+1\">";
              echo '<b>' .$key+1 . '.</b>  ';
              echo "<a href=\"./profile.php?user_id=".$value['subject_user_id']."\">". $value['subject_user_id']."</a>";
              echo "</font>";
              echo '  , <b>'. $value['AVG(score)'] . '</b> 点';

              echo "</p>";
              echo "</div>";
            }
          }
          echo "<br><br>";
          ?>
        </div>

      </div>

      <br>

</body>
</html>
