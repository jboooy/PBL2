<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <style>
    .marker01 {
      font-weight: bold;
      color: #DB7093;
      font-size: 30px;
      text-decoration-line: underline;
      text-underline-offset: 2px;
      text-decoration-thickness: 7px;
    }
  </style>
  <title>曲詳細画面</title>
  <link rel="stylesheet" href="./detail_css/main_tag.css">
  <link rel="stylesheet" href="./detail_css/border.css">
  <link rel="stylesheet" href="./detail_css/tab.css">
  <link rel="stylesheet" href="./detail_css/haikei.css">
  <link rel="stylesheet" href="./star_css/star.css">
  <link href="https://fonts.googleapis.com/earlyaccess/nicomoji.css" rel="stylesheet">
  <?php
  session_start();
  require '../vendor/autoload.php';
  require '../methods/api_request.php';
  require '../methods/db_m.php';
  require '../methods/sortByKey.php';

  $db = new GetReview();

  $track_score = $db->get_score(true, $_SESSION['comm_id']);

  $reviews = $db->get_all_track_review($_GET['spotify_id'], $_SESSION['comm_id']);

  $user_score = $db->get_score(false, $_SESSION['comm_id']);
  if (empty($user_score)) {
    $user_sorted = array();
  } else {
    $user_sorted = sortByKey("AVG(score)", SORT_DESC, $user_score);
  }

  $result = get_track_info($_GET['spotify_id']);
  $result1 = get_artist_info($result->tracks[0]->album->artists[0]->id);

  ?>
  <style>
    .border1 {
      width: 500px;
      height: 300px;
      margin: 0 auto;
    }

    .logo {
      top: 50px;
      width: 10px;
      height: 10px;
      position: relative;
    }

    img {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
    }

    h3 {
      font-family: "Nico Moji";
    }
  </style>
</head>

<body class="haikei">
  <div class="logo">
    <a href="home.php">
      <image src="KYOKULOG_logo.png"></image>
    </a>
  </div>

  <nav>
    <ul>
      <li><a class=”current” href="home.php"> Home </a></li>
      <li><a href="search.html"> Search </a></li>
      <li><a href="communitie.php"> Community </a></li>
      <li><a href="mypro_check.php"> Profile </a></li>
    </ul>
  </nav>

  <h2 style="text-align: center"><?= $result->tracks[0]->name ?></h3>
    <p style="text-align: center" class="marker01">
      <?php
      foreach ($track_score as $value) {
        if ($value['spotify_id'] == $_GET['spotify_id']) {
          echo round($value['AVG(score)'], 1) . "点";
          $this_track_score = round($value['AVG(score)'] / 20, 1);
        }
      }
      if (empty($this_track_score)) {
        $this_track_score = "non review";
      }
      ?>
    <div style="text-align: center">
      <span class="star5_rating" data-rate="<?= $this_track_score ?>"></span>
      <hr color="lime">
    </div>
    </p>


    <div class="border1" style="text-align: center">
      <div class="area">
        <input type="radio" name="tab_name" id="tab1" checked>
        <label class="tab_class" for="tab1">曲情報</label>
        <div class="content_class">
          <p>
            <?php
            echo "アーティスト名：" . $result->tracks[0]->artists[0]->name . "<br>";
            echo "収録アルバム名：" . $result->tracks[0]->album->name . "<br>";
            echo "アルバムリリース日：" . $result->tracks[0]->album->release_date . "<br>";
            echo "ジャンル：";
            if (isset($result1->genres[0])) {
              echo $result1->genres[0];
            } else {
              echo "情報がありません";
            }
            "<br>";
            ?>
          </p>
          <iframe src="https://open.spotify.com/embed/track/<?= $_GET['spotify_id'] ?>" width="70%" height="123" frameborder="0" allowtransparency="true" allow="encrypted-media">
          </iframe>
        </div>
        <input type="radio" name="tab_name" id="tab2">
        <label class="tab_class" for="tab2">レビューする</label>
        <div class="content_class">
          <form method="POST" action="../methods/song_eva.php">
            <p>
              評価：<input type="number" min="0" max="100" name="eva" size=20 required>
            </p>

            <br>

            <p>
              レビュー:<textarea name="review" rows=3 cols=40 required></textarea>
            </p>

            <br><br>

            <p>
              <input type="hidden" name="spotify_id" value="<?= $_GET['spotify_id'] ?>">
              <input type="submit" value=" 送信 ">
            </p>
          </form>



        </div>
      </div>

    </div>
    <hr color="lime">
    <h3 style="text-align: center">レビュー</h3>

    <div style="text-align: center;">
      <?php
      //レビュー表示
      $done_review = array();
      if (!empty($reviews)) {
        foreach ($user_sorted as $index => $user) {
          foreach ($reviews as $index2 => $review) {
            if ($review['user_id'] == $user['subject_user_id']) {
              $user_flag = true;
              $user_url = "profile.php?user_id=" . $review['user_id'];
              echo $index . "：";
              echo "ユーザID：";
              echo "<a href=\"$user_url\">";
              echo $review['user_id'] . "</a>" . "<br>";
              echo "評価：" . $review['score'] . "<br>";
              echo "コメント：" . $review['comment'] . "<br><br>";
              array_push($done_review, $review['eva_id']);
            }
          }
        }

        foreach ($reviews as $review) {
          foreach ($done_review as $done) {
            if ($review['eva_id'] == $done) {
              continue 2;
            }
          }

          $user_url = "profile.php?user_id=" . $review['user_id'];
          echo "未評価のユーザID：";
          echo "<a href=\"$user_url\">";
          echo $review['user_id'] . "</a>" . "<br>";
          echo "評価：" . $review['score'] . "<br>";
          echo "コメント：" . $review['comment'] . "<br>";
        }
      } //評価がないとき
      else {
        echo "There's no review on this song...<br>";
        echo "You can be a first reviewer of this song!!!";
      }

      ?>
    </div>



</body>

</html>