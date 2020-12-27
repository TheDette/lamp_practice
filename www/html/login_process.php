<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザー承認用の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'user.php';

// セッションスタート
session_start();

// ログイン済みか確認
if(is_logined() === true){
  // ログイン済なら、商品一覧ページへリダイレクトする
  redirect_to(HOME_URL);
}

// フォーム要素で入力したユーザー名を変数に格納
$name = get_post('name');
// フォーム要素で入力したパスワードを変数に格納
$password = get_post('password');

// DBに接続
$db = get_db_connect();

// フォーム要素で入力したユーザー名、またはパスワードが一致しなければ、ログインページへ戻り、「ログインに失敗しました。」と表示させる
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

// ユーザー名、パスワードが一致すればユーザータイプに該当するページへ移動し「ログインしました。」と表示させる。
set_message('ログインしました。');
// ユーザータイプが管理者であれば、商品管理ページへ移動する
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
// ユーザータイプが一般であれば商品一覧ページへ移動する
redirect_to(HOME_URL);
