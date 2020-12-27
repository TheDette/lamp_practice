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
// ログインしているユーザーの情報を取得し変数に格納
$user = get_login_user($db);

// form要素から指定商品カートidを取得し変数に格納
$cart_id = get_post('cart_id');
// form要素から指定商品のカートに入れた数量を取得し変数に格納
$amount = get_post('amount');

// カート内にある指定商品の数量を変更する処理
if(update_cart_amount($db, $cart_id, $amount)){
  // 処理に成功すればメッセージを取得する
  set_message('購入数を更新しました。');
} else {
  // 処理に失敗すればエラーメッセージを取得する
  set_error('購入数の更新に失敗しました。');
}

// カートページへ移動する
redirect_to(CART_URL);
