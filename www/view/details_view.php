<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($details) > 0){ ?>

        <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php print(h($details[0]['order_id'])); ?></td>
            <td><?php print(h($details[0]['created'])); ?></td>

          <?php $total_price = 0; ?>
          <?php foreach($details as $detail){ ?>
            <!-- 注文した商品全ての合計金額を計算 -->
            <?php $total_price += $detail['price'] * $detail['amount']; ?>
          <?php } ?>
            <!-- 合計金額を表示 -->
            <td><?php print number_format(h($total_price)); ?>円</td>
          </tr>
           
        </tbody>
      </table>
      
      <!-- 商品ごとの購入履歴を表示 -->
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>購入時の商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($details as $detail){ ?>
          <tr>
            <?php $total_price = 0; ?>
            <td><?php print(h($detail['name'])); ?></td>
            <td><?php print(h($detail['price'])); ?></td>
            <td><?php print(h($detail['amount'])); ?></td>
            <!-- 小計を計算 -->
            <?php $total_price = $detail['price'] * $detail['amount']; ?>
            <!-- 小計を表示 -->
            <td><?php print number_format(h($total_price)); ?>円</td>
          </tr>
           <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入明細データを取得する処理でエラーが発生しました。</p>
    <?php } ?> 

    <p class="text-right"><a href="order.php">購入履歴ページへ戻る</a></p>

  </div>
</body>
</html>
