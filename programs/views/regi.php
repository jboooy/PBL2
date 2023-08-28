<!DOCTYPE html>
<html lang="ja">
    <head>
        <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
        <link href="https://fonts.googleapis.com/css?family=M+PLUS+1p" rel="stylesheet">
        <link rel="stylesheet" href="./login_css/login.css">
        <meta charset="utf-8">
        <title>登録画面</title>
    </head>
    <body>
        <header>
            <h1>新規登録</h1>
        </header>

        <?php
        $db_name = 'mysql:host=localhost;dbname=pbl2';
        $db_user = 'root';
        $db_pass = '';

        $user_id = $_POST["user_id"]; 
        $password = $_POST["password1"];
        $password1 = $_POST["password2"];   //２回目のパスワード
        $user_name = $_POST["user_name"];
        $age = $_POST["age"];
        $sex = $_POST["sex"];
        $favorite = $_POST["favorite"];

        if($password == $password1){
            $db = new pdo($db_name, $db_user, $db_pass);
            $ps=$db->query("SELECT * FROM user_table");
            while($r=$ps->fetch()){
                if($r[0] == $user_id){
                    echo '<p class="error">このユーザーIDは既に使用されています。</p>';
                    echo '<a href ="regi_form.html"><button class="button">戻る</button></a>';
                    exit;
                }
            }
            $sql=$db->prepare(("INSERT INTO user_table VALUE (:user_id, :user_name, :password, :age, :sex, :favorite)"));
            $sql->bindParam( ':user_id', $user_id, PDO::PARAM_STR);
            $sql->bindParam( ':user_name', $user_name, PDO::PARAM_STR);
            $sql->bindParam( ':password', $password, PDO::PARAM_STR);
            $sql->bindParam( ':age', $age, PDO::PARAM_STR);
            $sql->bindParam( ':sex', $sex, PDO::PARAM_STR);
            $sql->bindParam( ':favorite', $favorite, PDO::PARAM_STR);
            $sql->execute();
            echo '<p class="succes">登録成功しました。</p>';
            echo '<a href ="login_form.html"><button class="button">戻る</button></a>';
            $db = null;
        }else{
            echo '<p class="error">入力したパスワードが異なります。</p>';
            echo '<a href ="regi_form.html"><button class="button">戻る</button></a>';
            exit;
        }
        ?>
    </body>
</html>