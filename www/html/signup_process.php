<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザー承認用の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'user.php';

// セッション開始
session_start();

// ログイン済みか確認
if(is_logined() === true){
  // ログイン済みなら商品一覧ページへ移動する
  redirect_to(HOME_URL);
}

// フォーム要素で入力したユーザー名を変数に格納
$name = get_post('name');
// フォーム要素で入力したパスワードを変数に格納
$password = get_post('password');
// フォーム要素で入力した確認用パスワードを変数に格納
$password_confirmation = get_post('password_confirmation');

// DBに接続
$db = get_db_connect();

try{
  // ユーザー名とパスワードが正しい形式で入力されているか確認し、DBにユーザー名とパスワードを登録する関数
  $result = regist_user($db, $name, $password, $password_confirmation);
  // ユーザー名とパスワードが正しい形式で入力されていない
  if( $result=== false){
    // エラーメッセージを取得
    set_error('ユーザー登録に失敗しました。');
    // ユーザー登録ページへ移動する
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  // 関数、DB処理時にエラーがあればエラーメッセージを取得
  set_error('ユーザー登録に失敗しました。');
  // ユーザー登録ページへ移動する
  redirect_to(SIGNUP_URL);
}

// ユーザー登録に成功すれば、「ユーザー登録が完了しました。」のメッセージをセッション変数に格納する
set_message('ユーザー登録が完了しました。');
// 
login_as($db, $name, $password);
// 商品一覧ページへ移動する
redirect_to(HOME_URL);
