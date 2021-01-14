<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// CSRFへの対策のためのトークン生成
$csrf_token = get_csrf_token();

$db = get_db_connect();
$user = get_login_user($db);

$items = get_open_items($db);

// 並べ替えの選択状態の初期設定（新着順）
$sort = '1';

include_once VIEW_PATH . 'index_view.php';
