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
  // ログインしていないならログインページへ移動する
  redirect_to(LOGIN_URL);
}

// DBに接続
$db = get_db_connect();

// ログインしているユーザーの情報を取得
$user = get_login_user($db);

// ユーザータイプが管理者か確認
if(is_admin($user) === false){
  // 管理者でなければログインページへ移動
  redirect_to(LOGIN_URL);
}

// DBから商品データを取得し変数$itemsに格納
$items = get_all_items($db);
// 商品管理ビューファイル読み込み
include_once VIEW_PATH . '/admin_view.php';
