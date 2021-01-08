<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  return fetch_all_query($db, $sql, [$user_id]);
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  return fetch_query($db, $sql, [$user_id, $item_id]);

}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, ?)
  ";

  return execute_query($db, $sql, [$item_id, $user_id, $amount]);
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  return execute_query($db, $sql, [$amount, $cart_id]);
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, [$cart_id]);
}

// 購入処理をする関数
function purchase_carts($db, $carts){
  // トランザクション処理
  $db->beginTransaction();
  try{
    // 商品が非公開、購入数に対して在庫数が足りているかチェック
    if(!(validate_cart_purchase($carts))){
      $db->rollback();
      return false;
    }
    // 購入したユーザーと購入日時を記録する処理
    $order_id = insert_orders($db, $carts[0]['user_id']);
    if($order_id === false){
      $db->rollback();
      return false;
    }
    foreach($carts as $cart){
      // 購入した商品の在庫数を減らす処理
      if(!(update_item_stock($db, $cart['item_id'], $cart['stock'] - $cart['amount'])) ||
      // 購入した商品と購入数を記録する処理
      !(insert_purchases($db, $order_id, $cart['item_id'], $cart['price'], $cart['amount']))
      ){
        $db->rollback();
        return false;
      }
    }
    // カート内の商品データを削除する処理
    if(!(delete_user_carts($db, $carts[0]['user_id']))){
      $db->rollback();
      return false;
    }
    // コミット処理
    $db->commit();
    return true;
  } catch (PDOException $e) {
    // ロールバック処理
    $db->rollback();
    return false;
  }
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  return execute_query($db, $sql, [$user_id]);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

// ordersテーブルにデータを登録する関数
function insert_orders($db, $user_id){
  $sql = "
    INSERT INTO
      orders(
        user_id
      )
    VALUES(?)
  ";

  if(execute_query($db, $sql, [$user_id])){
    return $db->lastInsertId();
  } else {
    return false;
  }
}

// purchase_historysテーブルにデータを登録する関数
function insert_purchases($db, $order_id, $item_id, $price, $amount){
  $sql = "
    INSERT INTO
    purchases(
        order_id,
        item_id,
        price,
        amount
      )
    VALUES(?, ?, ?, ?)
  ";

  return execute_query($db, $sql, [$order_id, $item_id, $price, $amount]);
}
