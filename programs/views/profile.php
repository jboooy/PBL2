<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>プロフィール画面</title>
    <link rel="stylesheet" href="profile_style.css">
    <link rel="stylesheet" href="./star_css/star.css">
    <?php
    session_start();
    ?>
    <style>
        nav {
            text-align: center;
        }

        nav ul {
            margin: 0;
            padding: 0;
        }

        nav li {
            list-style: none;
            display: inline-block;
            width: 10%;
            min-width: 90px;
        }

        nav li:not(:last-child) {
            border-right: 2px solid #ddd;
        }

        nav a {
            text-decoration: none;
            color: #333;
        }

        nav a.current {
            color: #00B0F0;
            border-bottom: 2px solid #00B0F0;
        }

        nav a:hover {
            color: #F7CB4D;
            border-bottom: 2px solid #F7CB4D;
        }

        .margin {
            margin-bottom: 5em;
        }

        .rate-form {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rate-form input[type=radio] {
            display: none;
        }

        .rate-form label {
            position: relative;
            padding: 0 5px;
            color: #ccc;
            cursor: pointer;
            font-size: 35px;
        }

        .rate-form label:hover {
            color: #66cdaa;
        }

        .rate-form label:hover~label {
            color: #66cdaa;
        }

        .rate-form input[type=radio]:checked~label {
            color: #66cdaa;
        }


        *,
        *::after,
        *::before {
            box-sizing: border-box;
        }

        .accordion {
            width: 350px;
            max-width: 100%;
        }

        /*------------------------------

ここからアコーディオンのCSS

------------------------------*/
        /* チェックボックスは非表示 */
        .accordion-hidden {
            display: none;
        }

        /* Question部分 */
        .accordion-open {
            display: block;
            padding: 10px;
            background: #66cdaa;
            cursor: pointer;
            margin: 0;
            font-weight: 700;
            position: relative;
            /* 変更部分 */
        }

        /* 開閉状態を示すアイコン+の作成 */
        .accordion-open::before,
        .accordion-open::after {
            content: '';
            width: 20px;
            height: 3px;
            background: #000;
            position: absolute;
            top: 50%;
            right: 5%;
            transform: translateY(-50%);
        }

        /* 一本は縦にして+を作る */
        .accordion-open::after {
            transform: translateY(-50%) rotate(90deg);
            transition: .5s;
        }

        /* アコーディオンが開いたら縦棒を横棒にして-にする */
        .accordion-hidden:checked+.accordion-open:after {
            transform: translateY(-50%) rotate(0);
        }

        /* Answer部分 */
        .accordion-close {
            display: block;
            height: 0;
            overflow: hidden;
            padding: 0;
            opacity: 0;
            transition: 0.5s;
            /* 表示速度の設定 */
        }

        /* チェックボックスにチェックが入ったらAnswer部分を表示する */
        .accordion-hidden:checked+.accordion-open+.accordion-close {
            height: auto;
            opacity: 1;
            padding: 10px;
            background: #f5f5f5;
            font-weight: 700;
        }

        .tarea {
            display: inline-block;
            width: 100%;
            padding: 10px;
            border: 1px solid #999;
            box-sizing: border-box;
            background: #f2f2f2;
            margin: 0.5em 0;
            line-height: 1.5;
            height: calc(25em + 22px)
        }



        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out
        }

        .btn-info {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8
        }

        .btn-info:hover {
            color: #fff;
            background-color: #138496;
            border-color: #117a8b
        }

        .btn-info.focus,
        .btn-info:focus {
            color: #fff;
            background-color: #138496;
            border-color: #117a8b;
            box-shadow: 0 0 0 .2rem rgba(58, 176, 195, .5)
        }

        .btn-info.disabled,
        .btn-info:disabled {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8
        }

        .btn-info:not(:disabled):not(.disabled).active,
        .btn-info:not(:disabled):not(.disabled):active,
        .show>.btn-info.dropdown-toggle {
            color: #fff;
            background-color: #117a8b;
            border-color: #10707f
        }

        .btn-info:not(:disabled):not(.disabled).active:focus,
        .btn-info:not(:disabled):not(.disabled):active:focus,
        .show>.btn-info.dropdown-toggle:focus {
            box-shadow: 0 0 0 .2rem rgba(58, 176, 195, .5)
        }

        .right {
            margin: 0 0 0 270px;

        }


        .User-info {
            display: flex;
        }

        .star {
            color: #66cdaa;
        }

        .flex {
            margin: auto 0;
            font-size: 20px;
        }
    </style>
    <?php
    require '../methods/db_m.php';
    require '../methods/sortByKey.php';

    $db = new GetUeserAndComData();
    $db_review = new GetReview();

    $user_prof = $db->get_profile($_GET['user_id']);

    $reviews = $db_review->get_all_user_review($_GET['user_id'], $_SESSION['comm_id']);
    $user_score = $db_review->get_score(false, $_SESSION['comm_id']);
    if (empty($user_score)) {
        $user_sorted = array();
    } else {
        $user_sorted = sortByKey("AVG(score)", SORT_DESC, $user_score);
    }

    foreach ($user_score as $each_user) {
        if ($each_user['subject_user_id'] == $_GET['user_id']) {
            $this_user_score = $each_user['AVG(score)'] / 20;
        }
    }
    if (empty($this_user_score)) {
        $this_user_score = "non review";
    }
    ?>
</head>

<body>
    <nav>
        <ul>
            <li><a class=”current” href="home.php">Home</a></li>
            <li><a href="search.html">Search</a></li>
            <li><a href="communitie.php">Community</a></li>
            <li><a href="mypro_check.php">Profile</a></li>
        </ul>
    </nav>
    <section>
        <div class="container">
            <div class="main-body">
                <div class="row gutters-sm">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150" id="profile_image">
                                    <div class="mt-3">
                                        <div class="User-info">
                                            <h4><?= $user_prof[0]['user_name'] ?></h4>
                                            <h4>　　</h4>
                                            <p class="result-rating-rate">
                                                <span class="star5_rating" data-rate="<?=$this_user_score?>"></span>
                                            </p>
                                            <div class="flex">｜</div>
                                            <div class="flex">(<?= $this_user_score ?>)</div>
                                        </div>
                                        <p class="text-secondary mb-1"></p>
                                        <div class="accordion">
                                            <form action="../methods/user_eva.php" method="post">
                                                <input type="checkbox" id="check1" class="accordion-hidden">
                                                <label for="check1" class="accordion-open">Evaluate</label>
                                                <label for="check1" class="accordion-close">
                                                    <hr>
                                                    <div class="rate-form">
                                                        <input id="star5" type="radio" name="rate" value="5">
                                                        <label for="star5">★</label>
                                                        <input id="star4" type="radio" name="rate" value="4">
                                                        <label for="star4">★</label>
                                                        <input id="star3" type="radio" name="rate" value="3">
                                                        <label for="star3">★</label>
                                                        <input id="star2" type="radio" name="rate" value="2">
                                                        <label for="star2">★</label>
                                                        <input id="star1" type="radio" name="rate" value="1">
                                                        <label for="star1">★</label>
                                                    </div>
                                                    <hr>
                                                    <textarea class="tarea" name="comment" placeholder="レビューを書き込む（800文字以下）"></textarea>
                                                    <hr>
                                                    <button class="btn btn-info right">Send</button>
                                                    <input type="hidden" name="sub_user" value=<?= $_GET['user_id'] ?>>
                                                </label>
                                            </form>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Favorite Genre♪</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?= $user_prof[0]['favoite'] ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Community♪</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        ＃HIP HOP好きの集い
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3">
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
                                            echo "There's no review on this user...<br>";
                                            echo "You can be a first reviewer of this user!!!";
                                        }

                                        ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </div>

    </section>
    <a href="../methods/logout.php">ログアウト</a>
</body>

</html>