<?php
// 変数の中身や配列の内容を出力する関数
function dd($var){
  // var_dump関数で引数に入れた変数の中身や配列の内容を出力する
  var_dump($var);
  // 関数を終了
  exit();
}

// 引数に入れたパスへリダイレクトする関数
function redirect_to($url){
  // 指定パスへリダイレクトする
  header('Location: ' . $url);
  // 関数を終了
  exit;
}

// getメソッドから送られてきたデータを取得する関数。引数にフォーム部品のnameに設定した値を入れる
function get_get($name){
  // getメソッドかつnameに設定した値であり、かつ中身あるか確認する
  if(isset($_GET[$name]) === true){
    // 中身があれば中身のデータを返す
    return $_GET[$name];
  };
  // 中身がなければ空を返す
  return '';
}

// postメソッドから送られてきたデータを取得できる関数。引数にフォーム部品のnameに設定した値を入れる
function get_post($name){
  // postメソッドかつnameに設定した値であり、かつ中身あるか確認する
  if(isset($_POST[$name]) === true){
    // 中身があれば中身のデータを返す
    return $_POST[$name];
  };
  // 中身がなければ空を返す
  return '';
}

// メソッドで送られてきた画像ファイル名を取得する関数
function get_file($name){
  // 引数に指定した変数$_FILESが存在するか確認
  if(isset($_FILES[$name]) === true){
    // 画像ファイル名を返す
    return $_FILES[$name];
  };
  // 変数が存在しなければ空の配列を返す
  return array();
}

// 引数に指定した$_SESSIONデータの中身を取得する関数
function get_session($name){
  // $_SESSIONの中身があればその中身のデータを返す
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  // $_SESSION[$name]の中身がなければ空を返す
  return '';
}

// セッションを登録する関数。第1引数にセッション名、第2引数に登録したいデータを入れる
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

// 引数に設定したエラーメッセージを$_SESSION['__errors']配列に格納する関数
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

// セッション変数に格納したエラーメッセージを取得する関数
function get_errors(){
  // $_SESSION['__errors']を取得する
  $errors = get_session('__errors');
  // $_SESSION['__errors']の中身がなければ空の配列を返す
  if($errors === ''){
    return array();
  }
  // $_SESSION['__errors']の中身を返し、$_SESSION['__errors']の中身を空にする
  set_session('__errors',  array());
  return $errors;
}

// エラーメッセージが$_SESSION['__errors']に格納されているか確認する関数
function has_error(){
  // $_SESSION['__errors']の中身に値がある場合、trueを返す。
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

// 引数に設定したメッセージを$_SESSION['__messages']配列に格納する関数
function set_message($message){
  // メッセージを$_SESSION['__messages']配列に格納
  $_SESSION['__messages'][] = $message;
}

// セッション変数に格納したメッセージを取得する関数
function get_messages(){
  // $_SESSION['__messages']を取得し変数に格納する
  $messages = get_session('__messages');
  // $_SESSION['__messages']の中身がなければ空の配列を返す
  if($messages === ''){
    return array();
  }
  // $_SESSION['__messages']の中身を返し、$_SESSION['__messages']の中身を空にする
  set_session('__messages',  array());
  // 変数を返す
  return $messages;
}

// $_SESSION['user_id']の中身があるか（ログイン済か）確認する関数
function is_logined(){
  // tureまたはfalseを返す
  return get_session('user_id') !== '';
}

// アップロードした画像ファイル名をランダムな文字列の名前に変更する関数
function get_upload_filename($file){
  // 画像ファイルが正しい方法、形式でアップロードされていない
  if(is_valid_upload_image($file) === false){
    // 空を返す
    return '';
  }
  // exif_imagetype関数は、引数に入れたファイルが画像ファイルであれば、画像ファイル形式に応じた定数を返す。変数$mimetypeに返り値を格納する
  $mimetype = exif_imagetype($file['tmp_name']);
  // 変数extに「jpg」または「png」を格納する
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  // 返り値としてランダムに生成された画像ファイル名を返す
  return get_random_string() . '.' . $ext;
}

// 20文字でランダムな文字列を生成する関数
function get_random_string($length = 20){
  // 返り値として20文字かつランダムな文字列を返す
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

// アップロードした画像ファイルをimagesフォルダに保存する関数
function save_image($image, $filename){
  // move_uploaded_file関数でアップロードした画像ファイルを該当するフォルダに保存する
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

// 指定商品画像を削除する関数
function delete_image($filename){
  // file_exists関数でimagesディレクトリに指定画像ファイルがあるか確認
  if(file_exists(IMAGE_DIR . $filename) === true){
    // unlink関数で指定画像ファイルを削除する
    unlink(IMAGE_DIR . $filename);
    // trueを返す
    return true;
  }
  // falseを返す
  return false;
  
}

// 第1引数に代入した文字数が第2引数以上、第3引数以下なのか確認する関数
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  // 変数$lengthにmb_strlen関数で第1引数の文字数を取得し格納する
  $length = mb_strlen($string);
  // $length（第1引数の文字数）が第2引数以上、第3引数以下なのか確認。返り値はtrueかfalse
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

// 正規表現によるマッチングを行う関数。引数にマッチングしたい文字列を代入。入力先頭から末尾まで半角英数字で入力されているかチェック。
function is_alphanumeric($string){
  // 返り値はtrueかfalse。
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

// 正規表現によるマッチングを行う関数。引数にマッチングしたい文字列を代入。入力先頭が1～9の半角数字、末尾まで0～9の半角数字が0回以上入力されているかチェック
function is_positive_integer($string){
  // 返り値はtrueかfalse。
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

// 正規表現によるマッチングを行う関数。第1引数にマッチングをしたい文字列を代入。第2引数に正規表現を代入。
function is_valid_format($string, $format){
  // preg_match関数で正規表現によるマッチングを行う。返り値はtrueかfalse。
  return preg_match($format, $string) === 1;
}

// 画像ファイルが正しい方法、形式でアップロードされているか確認する関数
function is_valid_upload_image($image){
  // POSTメソッドで画像ファイルがアップロードされていない
  if(is_uploaded_file($image['tmp_name']) === false){
    // エラーメッセージを取得
    set_error('ファイル形式が不正です。');
    // falseを返す
    return false;
  }
  // exif_imagetype関数は、引数に入れたファイルが画像ファイルであれば、画像ファイル形式に応じた定数を返す。変数$mimetypeに返り値を格納する
  $mimetype = exif_imagetype($image['tmp_name']);
  // アップデートした画像形式が「jpg」または「png」ではない
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    // エラーメッセージを取得。implode関数で定数PERMITTED_IMAGE_TYPES配列の内容を連結させて表示させる
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    // falseを返す
    return false;
  }
  // 画像ファイルが正しい方法、形式でアップロードされていればtrueを返す
  return true;
}

// 引数の値をHTMLエンティティ化する関数
function h($str){
  // HTMLエンティティ化した値を返す
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
