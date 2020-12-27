<?php 
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// DB接続用の関数をまとめたファイルを読み込み
require_once MODEL_PATH . 'db.php';

// 指定ユーザーidのカート内にある商品データを全て取得する関数
function get_user_carts($db, $user_id){
  // SQL文を生成
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
      carts.user_id = {$user_id}
  ";
  // SQL文を実行し、DBから取得したデータを配列で返す。
  return fetch_all_query($db, $sql);
}

// 指定ユーザーidのカート内にある指定商品idの商品データを取得する関数
function get_user_cart($db, $user_id, $item_id){
  // SQL文を生成
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
      carts.user_id = {$user_id}
    AND
      items.item_id = {$item_id}
  ";
  // SQL文を実行し、DBから取得したデータを返す。取得できなければfalseを返す。
  return fetch_query($db, $sql);

}

// カート内に商品を追加および数量を加算処理をする関数
function add_cart($db, $user_id, $item_id ) {
  // 指定商品がすでにカート内に入っているか確認
  $cart = get_user_cart($db, $user_id, $item_id);
  // 指定商品がまだカートに入っていない
  if($cart === false){
    // カート内に指定商品を登録する
    return insert_cart($db, $user_id, $item_id);
  }
  // 指定商品がすでにカート内にある場合、カート内の数量を一つ増やす
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

// カート内に商品を追加処理をする関数
function insert_cart($db, $user_id, $item_id, $amount = 1){
  // SQL文を生成
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";
  // SQL文を実行する。処理に失敗すればfalseを返す
  return execute_query($db, $sql);
}

// カート内にある指定商品の数量を変更処理する関数
function update_cart_amount($db, $cart_id, $amount){
  // SQLを生成
  $sql = "
    UPDATE
      carts
    SET
      amount = {$amount}
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  // SQLを実行。処理に失敗すればfalseを返す
  return execute_query($db, $sql);
}

// カート内にある指定商品をカート内から削除処理をする関数
function delete_cart($db, $cart_id){
  // SQLを生成
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  // SQL文を実行。成功すればtrue、失敗すればfalseを返す
  return execute_query($db, $sql);
}

function purchase_carts($db, $carts){
  // カート内の商品が購入できない状態である
  if(validate_cart_purchase($carts) === false){
    // falseを返す
    return false;
  }
  // カート内の商品データを１つずつ変数に格納し処理する
  foreach($carts as $cart){
    // 指定商品の在庫数を更新する
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        // 在庫数から購入する商品の数量分を引き算する
        $cart['stock'] - $cart['amount']
      ) === false){
      // 指定商品の在庫数を更新する処理に失敗すればエラーメッセージを取得する
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  // カート内から商品データを全て削除する
  delete_user_carts($db, $carts[0]['user_id']);
}

// 指定ユーザーのカート内から商品データを全て削除する関数
function delete_user_carts($db, $user_id){
  // SQL文を生成
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";
  // SQL文を実行
  execute_query($db, $sql);
}

// カート内商品の金額の合計を計算する関数
function sum_carts($carts){
  // 金額の合計変数を初期化。初期値は0円
  $total_price = 0;
  // カート内の商品データを一つずつ取り出す
  foreach($carts as $cart){
    // 商品金額✕数量
    $total_price += $cart['price'] * $cart['amount'];
  }
  // 計算した合計金額を返す
  return $total_price;
}

// カート内の商品が購入できるか確認する関数
function validate_cart_purchase($carts){
  // カート内に何も入っていない
  if(count($carts) === 0){
    // エラーメッセージを取得
    set_error('カートに商品が入っていません。');
    // falseを返す
    return false;
  }
  // カート内の商品を１つずつ変数に格納し、一つずつチェックする
  foreach($carts as $cart){
    // 商品が「非公開」になっている
    if(is_open($cart) === false){
      // エラーメッセージを取得
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // 在庫数が足りない
    if($cart['stock'] - $cart['amount'] < 0){
      // エラーメッセージを取得
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  // エラーメッセージを取得している
  if(has_error() === true){
    // falseを返す
    return false;
  }
  // カート内の商品が購入できる状態だと確認できたらtrueを返す
  return true;
}

