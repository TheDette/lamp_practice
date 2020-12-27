<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';

// セッション開始
session_start();

// ログイン済みか確認
if(is_logined() === true){
  // ログイン済なら、商品一覧ページへリダイレクトする
  redirect_to(HOME_URL);
}

// ログインビューファイル読み込み
include_once VIEW_PATH . 'login_view.php';
