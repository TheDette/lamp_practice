<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if((count($orders) > 0) && (count($purchases) > 0)){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>注文の合計金額</th>
            <th>注文の明細</th>
          </tr>
        </thead>
        <tbody>
        <!-- 注文番号ごとの購入履歴を表示 -->
          <?php foreach($orders as $order){ ?>
          <tr>
            <?php $order_id = $order['order_id']; ?>
            <!-- 合計金額の初期化 -->
            <?php $total_price = 0; ?>
            <td><?php print(h($order_id)); ?></td>
            <td><?php print(h($order['created'])); ?></td>

            <!-- 注文番号が同じ購入商品の合計金額を計算する -->
            <?php foreach($purchases as $purchase){ ?>
              <?php if($order_id !== $purchase['order_id']){continue;} ?>
              <?php $total_price += $purchase['price'] * $purchase['amount']; ?>
            <?php } ?>
            
            <!-- 注文番号ごとの合計金額を表示 -->
            <td><?php print number_format(h($total_price)); ?>円</td>

            <td>
              <!-- 注文番号ごとの購入明細ページへ移動するフォーム -->
              <form method="post" action="details.php">
                <input type="submit" value="購入明細表示" class="btn btn-primary">
                <!-- postデータに注文番号を格納 -->
                <input type="hidden" name="order_id" value="<?php print(h($order['order_id'])); ?>">
                <input type="hidden" name="csrf_token" value="<?php print($csrf_token); ?>">
              </form>
            </td>
           </tr>
           <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入履歴はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>
