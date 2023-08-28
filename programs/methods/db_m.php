<?php

class GetReview{
    
    //PDOインスタンス
    private $dsn = 'mysql:dbname=pbl2;host=localhost';
    private $user = 'root';
    private $password = '';

    //com毎のtrackレビュー
    public function get_all_track_review($sp_id, $communitie_id){
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "SELECT * FROM track_evaluation_table WHERE spotify_id = :spotify_id AND communitie_id = :communitie_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":spotify_id", $sp_id, PDO::PARAM_STR);
            $stmt->bindParam(":communitie_id", $communitie_id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
        return $result;
    }


    //com毎のuserレビュー
    public function get_all_user_review($sub_user_id, $communitie_id){
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "SELECT * FROM user_evaluation_table WHERE subject_user_id = :subject_user_id AND communitie_id = :communitie_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":subject_user_id", $sub_user_id, PDO::PARAM_STR);
            $stmt->bindParam(":communitie_id", $communitie_id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
        return $result;
    }

    //scoreの計算メソッド
    //type = true :track type = false :user, 
    public function get_score($type, $communitie_id){
        if( !is_bool($type) ){
            return false;
        }
        if( $type == true ){
            $field = 'track_evaluation_table';
            $idtype = 'spotify_id';
        }
        else{
            $field = 'user_evaluation_table';
            $idtype = 'subject_user_id';
        }
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "SELECT $idtype, AVG(score) FROM $field WHERE communitie_id = :communitie_id GROUP BY $idtype";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":communitie_id", $communitie_id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
        return $result;
    }
}

class GetUeserAndComData{
    //PDOインスタンス
    private $dsn = 'mysql:dbname=pbl2;host=localhost';
    private $user = 'root';
    private $password = '';

    //全ユーザー情報取得
    public function get_all_users(){
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "SELECT * FROM user_table";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
        return $result;
    }

    //指定user情報取得
    public function get_profile($user_id){
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "SELECT * FROM user_table WHERE user_id = :user_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
        return $result;
    }

    //全コミュニティ情報取得
    public function get_all_communities(){
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "SELECT * FROM communitie_table";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
        return $result;
    }

    //指定communitie情報取得
    public function get_communitie_info($communitie_id){
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "SELECT * FROM communitie_table WHERE communitie_id = :communitie_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":communitie_id", $communitie_id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
        return $result;
    }
}

class DataIns{
    //PDOインスタンス
    private $dsn = 'mysql:dbname=pbl2;host=localhost';
    private $user = 'root';
    private $password = '';

    //track_evaluation_tableへのインサート
    public function track_eva($user_id, $spotify_id, $communitie_id, $score, $comment){
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "INSERT INTO track_evaluation_table (user_id, spotify_id, communitie_id, score, comment)
            VALUES (:user_id, :spotify_id, :communitie_id, :score, :comment)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_STR);
            $stmt->bindParam(":spotify_id", $spotify_id, PDO::PARAM_STR);
            $stmt->bindParam(":communitie_id", $communitie_id, PDO::PARAM_STR);
            $stmt->bindParam(":score", $score, PDO::PARAM_STR);
            $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
        return $result;
    }

    //user_evaluation_tableへのインサート
    public function user_eva($user_id, $subject_user_id, $communitie_id, $score, $comment){
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "INSERT INTO user_evaluation_table (user_id, subject_user_id, communitie_id, score, comment)
            VALUES (:user_id, :subject_user_id, :communitie_id, :score, :comment)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_STR);
            $stmt->bindParam(":subject_user_id", $subject_user_id, PDO::PARAM_STR);
            $stmt->bindParam(":communitie_id", $communitie_id, PDO::PARAM_STR);
            $stmt->bindParam(":score", $score, PDO::PARAM_STR);
            $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
    }


    //communitie_tableへのインサート
    public function set_communitie( $communitie_name, $made_user_id, $communitie_description ){
        try{
            $dbh = new PDO($this->dsn, $this->user, $this->password);
            $sql = "INSERT INTO communitie_table (communitie_name, made_user_id, communitie_description)
            VALUES (:communitie_name, :made_user_id, :communitie_description)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":communitie_name", $communitie_name, PDO::PARAM_STR);
            $stmt->bindParam(":made_user_id", $made_user_id, PDO::PARAM_STR);
            $stmt->bindParam(":communitie_description", $communitie_description, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print('Error:'.$e->getMessage());
            die();
        }
    }
}

//この下の処理を他画面で呼び出す。

//実際は曲詳細画面から取得
//$spotify_id = '7iN1s7xHE4ifF5povM6A48';
//実際は他画面から取得
//$user_id = 'john1234';
//$subject_user_id = 'paul1234';
//$communitie_id = 1;
//$score = 55;
//$comment = "oh-no!";

//com
//$communitie_name = 'R&Bs';
//$made_user_id = 'john1234';
//$communitie_description = 'who loves R&Bs';

//$db_review = new GetReview();
//$db_user_and_com = new GetUeserAndComData();
//$db_ins = new DataIns();
//$res1 = $db_review->get_all_track_review($spotify_id,$communitie_id);
//$res2 = $db_review->get_all_user_review($subject_user_id, $communitie_id);
//$res3 = $db_review->get_score('a', $communitie_id);
//$res4 = $db_user_and_com->get_all_users();
//$res5 = $db_user_and_com->get_profile($user_id);
//$res6 = $db_user_and_com->get_all_communities();
//$res7 = $db_user_and_com->get_communitie_info($communitie_id);
//$db_ins->user_eva($user_id, $subject_user_id, $communitie_id, $score, $comment);
//$db_ins->set_communitie($communitie_name, $made_user_id, $communitie_description);
//var_dump($res1);
//var_dump($res2);
//var_dump($res3);
//var_dump($res4);
//echo $res5[0]['user_name'];
//var_dump($res6);
//var_dump($res7);

?>