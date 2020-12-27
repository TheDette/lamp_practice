<?php

// DBに接続する関数
function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    // DBに接続できなければエラーメッセージを取得する
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  // DB情報を返す
  return $dbh;
}

// DBからデータを１行取得する関数
function fetch_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQLを実行
    $statement->execute($params);
    // 返り値にデータを一行返す
    return $statement->fetch();
  }catch(PDOException $e){
    // DB接続時にエラーがあればエラーメッセージを取得する
    set_error('データ取得に失敗しました。');
  }
  // DB接続時にエラーがあればfalseを返す 
  return false;
}

// DBからデータを複数行取得する関数
function fetch_all_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQLを実行
    $statement->execute($params);
    // 返り値にデータを複数行返す
    return $statement->fetchAll();
  }catch(PDOException $e){
    // DB接続時にエラーがあればエラーメッセージを取得する
    set_error('データ取得に失敗しました。');
  }
  // DB接続時にエラーがあればfalseを返す
  return false;
}

// SQLを実行する関数
function execute_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // 返り値としてSQLを実行
    return $statement->execute($params);
  }catch(PDOException $e){
    // DB処理時にエラーがあればメッセージを取得する
    set_error('更新に失敗しました。');
  }
  // エラーがあればfalseを返す
  return false;
}
