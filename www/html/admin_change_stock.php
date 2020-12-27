<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ログイン、ユーザー登録の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'user.php';
// 商品登録、変更、データ取得の関数をまとめたファイルを読み込み 
require_once MODEL_PATH . 'item.php';

// セッションスタート
session_start();

// ログイン済みか確認
if(is_logined() === false){
  // ログインしていなければログインページへ移動する
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();

// ログインしているユーザーの情報を取得
$user = get_login_user($db);

// ユーザータイプが管理者か確認
if(is_admin($user) === false){
  // 管理者でなければログインページへ移動する
  redirect_to(LOGIN_URL);
}

// form要素から商品idを取得し変数に格納する
$item_id = get_post('item_id');
// form要素から変更在庫数を取得し変数に格納する
$stock = get_post('stock');

// 在庫数を変更する処理
if(update_item_stock($db, $item_id, $stock)){
  // 変更処理が成功すればメッセージを取得する
  set_message('在庫数を変更しました。');
} else {
  // 変更処理に失敗すればエラーメッセージを取得する
  set_error('在庫数の変更に失敗しました。');
}

// 商品管理ページへ移動する
redirect_to(ADMIN_URL);
