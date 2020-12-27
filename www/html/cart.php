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
// ログインしているユーザーの情報を取得し変数に格納する
$user = get_login_user($db);

// ログインしているユーザーのカート内の商品データを取得し変数に格納
$carts = get_user_carts($db, $user['user_id']);

// カート内の商品全ての合計金額を取得し変数に格納
$total_price = sum_carts($carts);

// カートビューファイル読み込み
include_once VIEW_PATH . 'cart_view.php';
