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
  
// DBから指定の注文番号の商品価格、購入数を取得する関数
function get_purchases($db, $order_id){
  $sql = "
    SELECT
      order_id,
      price,
      amount
    FROM
      purchases
    WHERE
      order_id = ?
  ";
  
  return fetch_all_query($db, $sql, [$order_id]);
}

// ログインユーザーの注文番号ごとの購入履歴データを取得する関数
function get_user_purchases($db, $orders){
  $purchases = array();
  // 注文番号を一つずつ抽出
  foreach($orders as $order){
    // 指定の注文番号の商品価格、購入数を取得する
    $purchase = get_purchases($db, $order['order_id']);
    // ↑で取得したデータを$purchases変数にarray_merge関数で合わせる
    $purchases = array_merge($purchases, $purchase);
  }
  return $purchases;
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
