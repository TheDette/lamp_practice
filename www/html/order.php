<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザー情報の取得、登録などの関数をまとめたファイル読み込み
require_once MODEL_PATH . 'user.php';
// 購入履歴を取得する関数をまとめたファイル読み込み
require_once MODEL_PATH . 'order.php';

// セッションスタート
session_start();

// ログイン済みか確認
if(is_logined() === false){
  // ログインしていなければログインページへ移動
  redirect_to(LOGIN_URL);
}

// CSRFへの対策のためのトークン生成
$csrf_token = get_csrf_token();

// DB接続
$db = get_db_connect();
// ログイン中のユーザーデータを取得
$user = get_login_user($db);

// ログインユーザーが管理者であれば全てのユーザーの注文番号と購入履歴を取得する
if($user['type'] === USER_TYPE_ADMIN){
  $orders = get_all_orders($db); 
  $purchases = get_all_purchases($db);
} else {
  // ログインユーザーの注文番号と購入履歴を取得する
  $orders = get_user_orders($db, $user['user_id']);
  $purchases = get_user_purchases($db, $orders);
}

// 購入履歴ビューファイルの読み込み
include_once VIEW_PATH . 'order_view.php';
