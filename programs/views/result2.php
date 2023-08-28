<!DOCTYPE html>
<html lang="ja">
<head>
<?php

require '../vendor/autoload.php';
require '../methods/api_request.php';

$result = search_track($_POST["search"]);
?>
</head>


<body>
    <h1>検索結果</h1>

    <?php
    $name = $_POST["search"];
    echo $name;
    echo '<br>';
    echo '<br>';
    foreach ($result->tracks->items as $track) {
        $url = "detail_song.php?spotify_id=".$track->id;
        echo "<a href=\"$url\">"; 
        echo $track->name."</a>";
        echo '<br><br>';
    }
    ?>
</body>
</html>