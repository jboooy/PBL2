<!DOCTYPE html>
<html lang="ja">
    <head>
        <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
        <link href="https://fonts.googleapis.com/css?family=M+PLUS+1p" rel="stylesheet">
        <link rel="stylesheet" href="./login_css/login.css">
        <meta charset="utf-8">
        <title>ログイン画面</title>
    </head>
    <body>
    <h1>ログイン</h1>

    <?php
    $db_name = 'mysql:host=localhost;dbname=pbl2';
    $db_user = 'root';
    $db_pass = '';

    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    $db = new pdo($db_name, $db_user, $db_pass);
    $ps=$db->query('SELECT * FROM user_table');

    //ユーザーID探し
    while($r=$ps->fetch()){
        if($r[0] == $user_id){
            if($r[2] == $password){
                session_start();
                $_SESSION['user_id'] = $user_id;

                header("Location:home.php");
                exit;
            }
            else {
                echo '<p class="error">ユーザーIDかパスワードが間違っています。</p>';
                echo '<a href ="login_form.html"><button class="button">戻る</button></a>';
                exit;
            }
        }
    }
    echo '<p class="error">ユーザーIDかパスワードが間違っています。</p>';
    echo '<a href ="login_form.html"><button class="button">戻る</button></a>';
?>
</body>
</html>