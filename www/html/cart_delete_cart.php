<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ログイン、ユーザー登録の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'user.php';
// 商品登録、変更、データ取得の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'item.php';
// カート内の商品データ取得、更新、変更する関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'cart.php';

// セッションスタート
session_start();

// ログイン済みか確認
if(is_logined() === false){
  // ログインしていなければログインページへ移動する
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();
// ログインしているユーザーの情報を取得する
$user = get_login_user($db);

// form要素からカート内から削除したい商品カートidを取得し変数に格納する
$cart_id = get_post('cart_id');

// 指定商品情報をカート内から削除する処理
if(delete_cart($db, $cart_id)){
  // 処理に成功すればメッセージを取得
  set_message('カートを削除しました。');
} else {
  // 処理に失敗すればエラーメッセージを取得
  set_error('カートの削除に失敗しました。');
}

// カートページへ移動する
redirect_to(CART_URL);
