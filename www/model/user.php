<?php
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// DB接続関連の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'db.php';

// DBから指定ユーザーidのデータを取得する関数
function get_user($db, $user_id){
  // LIMIT要素により、（同じidでデータが登録されていても）1行のみデータを取得するSQL文
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      user_id = {$user_id}
    LIMIT 1
  ";
  // 返り値としてSQL文を実行し、データを取得する
  return fetch_query($db, $sql);
}

// usersテーブルにある第2引数に指定したユーザー名を元にユーザーデータを取得する関数
function get_user_by_name($db, $name){
  // LIMIT要素により、（同じ名前でデータが登録されていても）1行のみデータを取得するSQL文
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      name = '{$name}'
    LIMIT 1
  ";
  // 返り値としてSQL文を実行し、データを取得する
  return fetch_query($db, $sql);
}

// ログインフォームで入力したユーザーネームとパスワードが一致しているかチェックする関数
function login_as($db, $name, $password){
  // ユーザーデータを取得する関数
  $user = get_user_by_name($db, $name);
  // 該当するユーザー名がDBに登録されていない、またはパスワードが一致していない場合falseを返す
  if($user === false || $user['password'] !== $password){
    return false;
  }
  // ユーザー名、パスワードが一致していればセッションにuser_idを登録し、ユーザーデータを返す
  set_session('user_id', $user['user_id']);
  return $user;
}

// セッション変数に格納されているuse_idを取得し、DBから指定use_idのデータを取得する関数
function get_login_user($db){
  // $login_user_id変数にセッション変数に格納されているuse_idを格納
  $login_user_id = get_session('user_id');
  // 返り値としてDBから指定use_idのデータを返す
  return get_user($db, $login_user_id);
}

// ユーザー名とパスワードが正しい形式で入力されているか確認し、DBにユーザー名とパスワードを登録する関数
function regist_user($db, $name, $password, $password_confirmation) {
  // ユーザー名とパスワードが正しい形式で入力されていない 
  if( is_valid_user($name, $password, $password_confirmation) === false){
    // falseを返す
    return false;
  }
  // ユーザー名とパスワードが正しい形式で入力されていればDBにユーザー名とパスワードを登録する
  return insert_user($db, $name, $password);
}

// ユーザータイプが管理者か確認する関数
function is_admin($user){
  // ユーザータイプが管理者か確認する。返り値はtrueかfalse
  return $user['type'] === USER_TYPE_ADMIN;
}

// ユーザー名とパスワードが正しい形式で入力されているか確認する関数
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  // ユーザー名が正しい形式で入力されているか確認。trueかfalseが返り値
  $is_valid_user_name = is_valid_user_name($name);
  // パスワードが正しい形式で入力されているか確認。trueかfalseが返り値
  $is_valid_password = is_valid_password($password, $password_confirmation);
  // それぞれの返り値を返す
  return $is_valid_user_name && $is_valid_password ;
}

// ユーザー名が正しい形式で入力されているか確認する関数
function is_valid_user_name($name) {
  // 変数$is_validの初期設定。trueを格納
  $is_valid = true;
  // ユーザー名が6文字以上100文字以下で入力されていない
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    // エラーメッセージを取得
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    // 変数$is_validにfalseを格納
    $is_valid = false;
  }
  // ユーザー名が半角英数字で入力されていない
  if(is_alphanumeric($name) === false){
    // エラーメッセージを取得
    set_error('ユーザー名は半角英数字で入力してください。');
    // 変数$is_validにfalseを格納
    $is_valid = false;
  }
  // 変数$is_validを返す。返り値はtrueかfalse
  return $is_valid;
}

// パスワードが正しい形式で入力されているか確認する関数
function is_valid_password($password, $password_confirmation){
  // 変数$is_validの初期設定。trueを格納
  $is_valid = true;
  // パスワードが6文字以上100文字以下で入力されていない
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    // エラーメッセージを取得
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    // 変数$is_validにfalseを格納
    $is_valid = false;
  }
  // ユーザー名が半角英数字で入力されていない
  if(is_alphanumeric($password) === false){
    // エラーメッセージを取得
    set_error('パスワードは半角英数字で入力してください。');
    // 変数$is_validにfalseを格納
    $is_valid = false;
  }
  // 入力したパスワードと再度入力したパスワードが一致していない
  if($password !== $password_confirmation){
    // エラーメッセージを取得
    set_error('パスワードがパスワード(確認用)と一致しません。');
    // 変数$is_validにfalseを格納
    $is_valid = false;
  }
  // 変数$is_validを返す。返り値はtrueかfalse
  return $is_valid;
}

// DBにユーザー名とパスワードを登録する関数
function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES ('{$name}', '{$password}');
  ";
  // 返り値としてSQL文を実行
  return execute_query($db, $sql);
}

