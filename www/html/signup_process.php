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
$password_confirmation = get_post('password_confirmation');
$csrf_token = get_post('csrf_token');

$db = get_db_connect();

// トークンのチェック
if(is_valid_csrf_token($csrf_token)){

  //  ユーザー登録処理
  if(regist_user($db, $name, $password, $password_confirmation)){
    
    set_message('ユーザー登録が完了しました。');
    login_as($db, $name, $password);
    redirect_to(HOME_URL);

  } else {
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }

} else {
  set_error('不正なリクエストです。');
  redirect_to(SIGNUP_URL);
}
