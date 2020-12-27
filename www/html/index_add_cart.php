<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ログイン、ユーザー登録の関数をまとめたファイルを読み込み 
require_once MODEL_PATH . 'user.php';
// 商品登録、変更、データ取得の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'item.php';
// カート追加、変数、データ取得の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'cart.php';

// セッションスタート
session_start();

// ログイン済みか確認
if(is_logined() === false){
  // ログインしてなければログインページへ移動する
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();
// ログインしているユーザーの情報を取得
$user = get_login_user($db);

// form要素から送られてきたカートに追加する商品idデータを変数$item_idに格納
$item_id = get_post('item_id');

// カートに商品を追加する処理
if(add_cart($db,$user['user_id'], $item_id)){
  // 処理が成功すればメッセージを取得
  set_message('カートに商品を追加しました。');
} else {
  // 処理が失敗すればエラーメッセージを取得
  set_error('カートの更新に失敗しました。');
}

// 商品一覧ページへ移動する
redirect_to(HOME_URL);
