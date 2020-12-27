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
  // ログインしていなければログインページへ移動
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();
// ログインしているユーザーの情報を取得し変数に格納
$user = get_login_user($db);
// ステータスが「公開」なっている商品データを取得すし変数に格納
$items = get_open_items($db);

// 商品一覧ビューファイル読み込み
include_once VIEW_PATH . 'index_view.php';
