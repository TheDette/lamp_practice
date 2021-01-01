<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id');
$csrf_token = get_post('csrf_token');

// トークンのチェック
if(is_valid_csrf_token($csrf_token)){

  // 指定商品情報を削除する処理
  if(destroy_item($db, $item_id)){
    set_message('商品を削除しました。');
  } else {
    set_error('商品削除に失敗しました。');
  }

} else {
  set_error('不正なリクエストです。');
}

redirect_to(ADMIN_URL);
