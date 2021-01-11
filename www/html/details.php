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

// DB接続
$db = get_db_connect();
// ログイン中のユーザーデータを取得
$user = get_login_user($db);

$order_id = get_post('order_id');
$csrf_token = get_post('csrf_token');

// トークンのチェック
if(is_valid_csrf_token($csrf_token)){

  // 該当の注文番号の購入明細を取得する処理
  $details = get_order_details($db, $order_id);
  
} else {
  set_error('不正なリクエストです。');
  redirect_to(ORDER_URL);
}

// 購入履歴ビューファイルの読み込み
include_once VIEW_PATH . 'details_view.php';
