<!DOCTYPE html>
<html lang="ja">

<head>
    <title>コミュニティ画面</title>

    <link rel="stylesheet" href="./detail_css/main_tag.css">
    <link rel="stylesheet" href="./detail_css/tab.css">
    <link rel="stylesheet" href="./detail_css/haikei.css">
    <?php

    require '../methods/db_m.php';
    require '../methods/login_method.php';

    //フォームで送られてきたコミュニティIDをsessionに入れ，更新。
    if (isset($_POST['comm_id'])) {
        $_SESSION['comm_id'] = $_POST['comm_id'];
    }

    //sessionが空の場合，1「general」をいれておく
    if (!isset($_SESSION['comm_id'])) {
        $_SESSION['comm_id'] = '1';
    }

    $db_read = new GetUeserAndComData;
    $db_ins = new DataIns();

    //仮のsessionUSER　※要削除
    //$_SESSION['user_id'] = 'john1234';

    //communitiy_insert
    if (isset($_POST['name'])) {
        if (login_check()) {
            $db_ins->set_communitie($_POST['name'], $_SESSION['user_id'], $_POST['desc']);
        } else {
            header('location:login_form.html');
        }
    }

    //community情報取得
    $communities = $db_read->get_all_communities();



    //ボタン変数
    $now_button = "     <input type =\"submit\"  value =\"NOW ON IT\" style=\"color:red\"><br>";
    $ava_button = "     <input type =\"submit\" value =\"AVAILABLE\" style=\"color:blue\"><br>";
    ?>
</head>

<body class="haikei">
    <nav>
        <ul>
            <li><a class=”current” href="home.php"> Home </a></li>
            <li><a href="search.html"> Search </a></li>
            <li><a href="communitie.php"> Community </a></li>
            <li><a href="mypro_check.php"> Profile </a></li>
        </ul>
    </nav>

    <div class="border" style="text-align: center">
        <h3>コミュニティ</h3>
        <hr>

        <div class="area">
            <input type="radio" name="tab_name" id="tab1" checked>
            <label class="tab_class" for="tab1">選択</label>
            <div class="content_class">
                <p>
                    <?php
                    foreach ($communities as $com) {
                        //毎時form(IDを入れる)を出力
                        $com_id = $com['communitie_id'];
                        echo "<form action=\"communitie.php\" method=\"post\">";
                        echo "<input type = \"hidden\" name=\"comm_id\" value=\"$com_id\">";

                        //コミュニティ名出力(id => name, description)
                        echo "<p style=\"font-weight:bold\">#" . $com['communitie_name'];

                        //sessionの値と 一致＝＞now button 不一致＝＞available button
                        if ($_SESSION['comm_id'] == $com_id) {
                            echo $now_button."</p>";
                        } else {
                            echo $ava_button."</p>";
                        }

                        //コミュニティ説明出力
                        echo "[DESCRIPPTION]<br>" . $com['communitie_description'] . "<br><br>";
                        echo "</form>";
                    }
                    ?>
                </p>
            </div>

            <input type="radio" name="tab_name" id="tab2">
            <label class="tab_class" for="tab2">作成</label>
            <div class="content_class">
                <form action="communitie.php" method="post">
                    <p>
                        コミュニティ名：<input type="text" name="name" size=30>
                    </p>

                    <br><br>

                    <p>
                        説明:<br>
                        <textarea name="desc" rows=12 cols=40></textarea>
                    </p>

                    <br><br>

                    <p>
                        <input type="submit" value=" 送信 ">
                    </p>
                </form>

            </div>
        </div>



    </div>
</body>

</html>