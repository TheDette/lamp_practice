<?php
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// DB接続用の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'db.php';

// DB利用

// DBに登録されている指定商品idの商品データを取得する関数
function get_item($db, $item_id){
  // SQL文を生成
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = {$item_id}
  ";
  // SQL文を実行し、DBから取得したデータを返す
  return fetch_query($db, $sql);
}

// DBに登録されている商品データを全て取得する関数
function get_items($db, $is_open = false){
  // SQL文を生成
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  // SQL文を実行し、DBから取得したデータを返す
  return fetch_all_query($db, $sql);
}

// DBに登録されている商品データを全て取得する関数
function get_all_items($db){
  // 返り値はDBに登録されている商品データを全て
  return get_items($db);
}

// DBに登録されている商品データを取得する関数
function get_open_items($db){
  // 返り値はステータスが「公開」になってる商品データ
  return get_items($db, true);
}

// 商品追加処理の関数をまとめた関数
function regist_item($db, $name, $price, $stock, $status, $image){
  // get_upload_filename関数で変数$filenameにfalseまたはランダムに生成された画像ファイル名を格納する
  $filename = get_upload_filename($image);
  // 追加する商品情報が正しく入力されていない
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    // falseを返す
    return false;
  }
  // 商品情報をDBに保存し、アップロードされた画像ファイルをimagesフォルダに保存する。返り値は処理が成功すればtrue、失敗すればfalse
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

// 商品情報をDBに保存し、アップロードされた画像ファイルを保存する関数
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  // トランザクション開始
  $db->beginTransaction();
  // 追加する商品情報をDBに登録する
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    // アップロードした画像ファイルをimagesフォルダに保存する
    && save_image($image, $filename)){
    // コミット処理
    $db->commit();
    // trueを返す
    return true;
  }
  // ロールバック処理
  $db->rollback();
  // falseを返す
  return false;
  
}

// 追加する商品情報をDBに登録する関数
function insert_item($db, $name, $price, $stock, $filename, $status){
  // $status値を0か1に変更し変数$status_valueに格納する
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES('{$name}', {$price}, {$stock}, '{$filename}', {$status_value});
  ";
  
  // SQL文を実行する
  return execute_query($db, $sql);
}

// 指定商品idのステータスを変更する関数
function update_item_status($db, $item_id, $status){
  // SQL文を生成
  $sql = "
    UPDATE
      items
    SET
      status = {$status}
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  // SQL文を実行する。成功すればtrue、失敗すればfalseを返す。 
  return execute_query($db, $sql);
}

// 指定商品idの在庫数を更新する関数
function update_item_stock($db, $item_id, $stock){
  // SQL文を生成
  $sql = "
    UPDATE
      items
    SET
      stock = {$stock}
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  // SQL文を実行する。成功すればtrue、失敗すればfalseを返す。
  return execute_query($db, $sql);
}

// 指定商品idの商品データと商品画像を削除する関数
function destroy_item($db, $item_id){
  // 削除する商品データをDBから取得する
  $item = get_item($db, $item_id);
  // 商品データがあるか確認する
  if($item === false){
    // 商品データがすでに無ければfalseを返す
    return false;
  }
  // トランザクション開始
  $db->beginTransaction();
  // 指定商品idの商品データを削除するDB処理
  if(delete_item($db, $item['item_id'])
    // 削除する商品の商品画像を削除する
    && delete_image($item['image'])){
    // コミット処理
    $db->commit();
    // DB処理が成功すればtrueを返す
    return true;
  }
  // ロールバック処理
  $db->rollback();
  // DB処理が失敗すればfalseを返す
  return false;
}

// 指定商品idの商品データを削除する関数
function delete_item($db, $item_id){
  // SQL文を生成
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  // SQL文を実行する。成功すればtrue、失敗すればfalseを返す。
  return execute_query($db, $sql);
}


// 非DB

// ステータスが公開であるか確認する関数
function is_open($item){
  // 返り値はtrueかfalse
  return $item['status'] === 1;
}

// 追加する商品情報が正しく入力されているか確認する関数
function validate_item($name, $price, $stock, $filename, $status){
  // 商品名が正しい形式で入力されているか確認する
  $is_valid_item_name = is_valid_item_name($name);
  // 金額が正しく入力されているか確認する
  $is_valid_item_price = is_valid_item_price($price);
  // 在庫数が正しく入力されているか確認する
  $is_valid_item_stock = is_valid_item_stock($stock);
  // ランダムな文字列の画像ファイル名が生成されているか確認する
  $is_valid_item_filename = is_valid_item_filename($filename);
  // ステータスが正しい形式か確認する
  $is_valid_item_status = is_valid_item_status($status);

  // チェックして返ってきた値を格納した変数を全て返す。値はtrueかfalse
  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

// 商品名が正しい形式で入力されているか確認する関数
function is_valid_item_name($name){
  // 変数is_validにtrueを格納
  $is_valid = true;
  // 商品名が1文字以上かつ100文字以下でない
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    // エラーメッセージを取得
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    // 変数is_validにfalseを格納
    $is_valid = false;
  }
  // 変数$is_validを返す
  return $is_valid;
}

// 金額が正しく入力されているか確認する関数
function is_valid_item_price($price){
  // 変数is_validにtrueを格納
  $is_valid = true;
  // 入力先頭が1～9の半角数字、末尾まで0～9の半角数字が0回以上入力されているかチェックする
  if(is_positive_integer($price) === false){
    // エラーメッセージを取得
    set_error('価格は0以上の整数で入力してください。');
    // 変数is_validにfalseを格納
    $is_valid = false;
  }
  // 変数$is_validを返す
  return $is_valid;
}

// 在庫数が正しく入力されているか確認する関数
function is_valid_item_stock($stock){
  // 変数is_validにtrueを格納
  $is_valid = true;
  // 入力先頭が1～9の半角数字、末尾まで0～9の半角数字が0回以上入力されているかチェックする
  if(is_positive_integer($stock) === false){
    // エラーメッセージを取得
    set_error('在庫数は0以上の整数で入力してください。');
    // 変数is_validにfalseを格納
    $is_valid = false;
  }
  // 変数$is_validを返す
  return $is_valid;
}

// ランダムな文字列の画像ファイル名が生成されているか確認する関数
function is_valid_item_filename($filename){
  // 変数is_validにtrueを格納
  $is_valid = true;
  // 変数$filenameの中身が空
  if($filename === ''){
    // 変数is_validにfalseを格納
    $is_valid = false;
  }
  // 変数$is_validを返す
  return $is_valid;
}

// ステータスが正しい形式か確認する関数
function is_valid_item_status($status){
  // 変数is_validにtrueを格納
  $is_valid = true;
  // ステータスが0か1でない
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    // 変数is_validにfalseを格納
    $is_valid = false;
  }
  // 変数$is_validを返す
  return $is_valid;
}
