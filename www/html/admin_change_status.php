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

// ログインしているユーザーの情報を取得
$user = get_login_user($db);

// ユーザータイプが管理者か確認
if(is_admin($user) === false){
  // 管理者でなければログインページへ移動する
  redirect_to(LOGIN_URL);
}

// form要素から送られてきた商品idを取得し変数に格納する
$item_id = get_post('item_id');
// form要素から送られてきた変更ステータスを取得し格納する
$changes_to = get_post('changes_to');

// 「非公開」→「公開」に変更する
if($changes_to === 'open'){
  // 変更処理を行う
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  // 変更処理に成功すればメッセージを取得
  set_message('ステータスを変更しました。');
// 「公開」→「非公開」に変更する
}else if($changes_to === 'close'){
  // 変更処理を行う
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  // 変更処理に成功すればメッセージを取得
  set_message('ステータスを変更しました。');
}else {
  // 変更処理に失敗すればエラーメッセージを取得
  set_error('不正なリクエストです。');
}

// 商品管理ページへ移動する
redirect_to(ADMIN_URL);
