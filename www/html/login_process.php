<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

if(is_logined() === true){
  redirect_to(HOME_URL);
}

$name = get_post('name');
$password = get_post('password');
$csrf_token = get_post('csrf_token');

$db = get_db_connect();

// トークンのチェック
if(is_valid_csrf_token($csrf_token)){

  // ログイン認証処理
  $user = login_as($db, $name, $password);
  if( $user === false){
    set_error('ログインに失敗しました。');
    redirect_to(LOGIN_URL);
  }

  set_message('ログインしました。');
  if ($user['type'] === USER_TYPE_ADMIN){
    redirect_to(ADMIN_URL);
  }

} else {
  set_error('不正なリクエストです。');
  redirect_to(LOGIN_URL);
}

redirect_to(HOME_URL);
