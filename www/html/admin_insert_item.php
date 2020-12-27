<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ログイン、ユーザー登録の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'user.php';
// 商品登録、データ取得の関数をまとめたファイルを読み込み
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

// ログインしているユーザーの情報を取得し変数$userに格納
$user = get_login_user($db);

// ログイン中のユーザーのタイプが管理者か確認
if(is_admin($user) === false){
  // 管理者でなければログインページへ移動する
  redirect_to(LOGIN_URL);
}

// フォーム要素で入力した商品名を取得し変数$nameに格納
$name = get_post('name');
// フォーム要素で入力した値段を取得し変数$priceに格納
$price = get_post('price');
// フォーム要素で選択したステータスを取得し変数$statusに格納
$status = get_post('status');
// フォーム要素で入力した在庫数を取得し変数$stockに格納
$stock = get_post('stock');

// フォーム要素で挿入した商品画像ファイル名を取得し変数$imageに格納
$image = get_file('image');

// 追加する商品情報が正しい形式で入力されているか確認し、DBに登録する
if(regist_item($db, $name, $price, $stock, $status, $image)){
  // DB処理が成功すればメッセージを取得する
  set_message('商品を登録しました。');
}else {
  // DB処理が失敗または商品情報が正しい形式で入力されていなければエラーメッセージを取得する
  set_error('商品の登録に失敗しました。');
}

// 商品管理ページへ移動する
redirect_to(ADMIN_URL);
