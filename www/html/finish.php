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
  // ログインしていなければログインページへ移動
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();
// ログインしているユーザーの情報を取得する
$user = get_login_user($db);

// 指定ユーザーidのカート内にある商品データを全て取得し変数に格納する 
$carts = get_user_carts($db, $user['user_id']);

// 購入処理をする
if(purchase_carts($db, $carts) === false){
  // 購入処理に失敗すればエラーメッセージを取得
  set_error('商品が購入できませんでした。');
  // カートページへ移動する
  redirect_to(CART_URL);
} 

// カート内にある全ての商品の合計金額を変数に格納
$total_price = sum_carts($carts);

// ご購入ありがとうございました！ビューファイル読み込み
include_once '../view/finish_view.php';
