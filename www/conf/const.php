<?php
/**
 * define関数で定数の設定
*/

// 「model」ディレクトリまでのパス（$_SERVER['DOCUMENT_ROOT']は現在実行されているスクリプトまでのパスを教えてくれる）
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
// 「view」ディレクトリまでのパス（const.php内だと、/MyDocker/lamp_practice/lamp_practice/www/conf/../view/ となる)
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');

// 「images」ディレクトリまでのパス
define('IMAGE_PATH', '/assets/images/');
// 「css」ディレクトリまでのパス
define('STYLESHEET_PATH', '/assets/css/');
// $_SERVER['DOCUMENT_ROOT']を使用した「images」ディレクトリまでのパス
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );

// データベース名
define('DB_HOST', 'mysql');
// mysqlのデータベース名
define('DB_NAME', 'sample');
// mysqlのユーザー名
define('DB_USER', 'testuser');
// mysqlのパスワード
define('DB_PASS', 'password');
// mysqlの文字コード
define('DB_CHARSET', 'utf8');

// 「signup.php」までのパス
define('SIGNUP_URL', '/signup.php');
// 「login.php」までのパス
define('LOGIN_URL', '/login.php');
// 「logout.php」までのパス
define('LOGOUT_URL', '/logout.php');
// 「index.php」までのパス
define('HOME_URL', '/index.php');
// 「cart.php」までのパス
define('CART_URL', '/cart.php');
// 「finish.php」までのパス
define('FINISH_URL', '/finish.php');
// 「admin.php」までのパス
define('ADMIN_URL', '/admin.php');

// 入力先頭から末尾まで半角英数字で入力されているかチェックする正規表現
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
// 入力先頭が1～9の半角数字、末尾まで0～9の半角数字が0回以上入力されているかチェックする正規表現
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');

// ユーザー名は6文字以上にする
define('USER_NAME_LENGTH_MIN', 6);
// ユーザー名は100文字以下にする
define('USER_NAME_LENGTH_MAX', 100);
// パスワードは6文字以上にする
define('USER_PASSWORD_LENGTH_MIN', 6);
// パスワードは100文字以下にする
define('USER_PASSWORD_LENGTH_MAX', 100);

// ユーザータイプが管理者
define('USER_TYPE_ADMIN', 1);
// ユーザータイプが一般
define('USER_TYPE_NORMAL', 2);

// アイテム名は1文字以上にする
define('ITEM_NAME_LENGTH_MIN', 1);
// アイテム名は100文字以下にする
define('ITEM_NAME_LENGTH_MAX', 100);

// 登録アイテムを公開にする
define('ITEM_STATUS_OPEN', 1);
// 登録アイテムを非公開にする
define('ITEM_STATUS_CLOSE', 0);

// 配列定数。アイテムのステータスが公開または非公開なのか
define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));

// 配列定数。アップデートした画像形式が「jpg」または「png」なのか
define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));
