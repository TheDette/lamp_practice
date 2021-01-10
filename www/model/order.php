<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

// 全てのユーザーの注文番号、購入日時を取得する関数
function get_all_orders($db){
  $sql = "
    SELECT
      order_id,
      created
    FROM
      orders
  ";

  return fetch_all_query($db, $sql);
}

// 指定ユーザーが購入した時の注文番号、購入日時を取得する関数
function get_user_orders($db, $user_id){
  $sql = "
    SELECT
      order_id,
      created
    FROM
      orders
    WHERE
      user_id = ?
  ";

  return fetch_all_query($db, $sql, [$user_id]);
}

// DBから全ての購入商品履歴を取得する処理
function get_all_purchases($db){
  $sql = '
    SELECT
      order_id, 
      price,
      amount
    FROM
      purchases
  ';

  return fetch_all_query($db, $sql);
}
  
// IN句に指定した複数の注文番号の商品価格、購入数を取得する関数
function get_in_purchases($db, $in){
  $sql = "
    SELECT
      order_id,
      price,
      amount
    FROM
      purchases
    WHERE
      order_id
    IN(
      $in
    )
  ";
  
  return fetch_all_query($db, $sql);
}

// 注文番号の商品データを取得する関数
function get_order_details($db, $order_id){
  $sql = "
    SELECT
      purchases.order_id,
      purchases.item_id,
      purchases.price,
      purchases.amount,
      purchases.created,
      items.name
    FROM
      purchases
    JOIN
      items
    ON
      purchases.item_id = items.item_id
    WHERE
      purchases.order_id = ?
  ";

  return fetch_all_query($db, $sql, [$order_id]);
}

// DBから指定の注文番号の商品価格、購入数を取得する関数
function get_user_purchases($db, $orders){
  // 連想配列で格納された複数の注文番号を文字列連結させ、変数$inに格納する
  $in = link_order_id($orders);
  // purchasesテーブルから指定の注文番号の商品価格、購入数を取得して返す
  return get_in_purchases($db, $in);
}  

// 連想配列で格納された複数の注文番号を一つの変数に連結し格納させる関数
function link_order_id($orders) {
  foreach($orders as $order){
    $in .= $order['order_id'] . ",";
  }
  // 連結させた文字列の末尾","を削除して返す
  return rtrim($in, ",");
}
