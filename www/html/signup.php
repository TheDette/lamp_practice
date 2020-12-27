<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';

// セッションスタート
session_start();

// ログイン済みか確認
if(is_logined() === true){
  // ログイン済みなら商品一覧ページへ移動
  redirect_to(HOME_URL);
}

// サインアップビューファイル読み込み
include_once VIEW_PATH . 'signup_view.php';



