<!-- htmlのバージョン宣言 -->
<!DOCTYPE html>
<!-- このページのhtml要素は日本語を適応する -->
<html lang="ja">
<!-- 以下にこのページの概要を記載 -->
<head>
  <!-- ページ概要テンプレートファイル読み込み -->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <!-- このページのタイトル -->
  <title>カート</title>
  <!-- このページ用のスタイルシート読み込み -->
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<!-- 以下からこのページのブラウザで表示させる部分 -->
<body>
  <!-- ヘッダーテンプレートファイル読み込み -->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <!-- テキストを見出しで表示 -->
  <h1>カート</h1>
  <!-- ヘッダー以外のコンテンツをcontainerクラスで囲む -->
  <div class="container">

    <!-- 何かメッセージがあれば表示させる -->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <!-- 商品がカートに入っていれば以下に表示 -->
    <?php if(count($carts) > 0){ ?>
      <!-- カート内商品はテーブル要素で表示させる -->
      <table class="table table-bordered">
        <!-- 商品データの項目名 -->
        <thead class="thead-light">
          <tr>
            <!-- 項目名 -->
            <th>商品画像</th>
            <!-- 項目名 -->
            <th>商品名</th>
            <!-- 項目名 -->
            <th>価格</th>
            <!-- 項目名 -->
            <th>購入数</th>
            <!-- 項目名 -->
            <th>小計</th>
            <!-- 項目名 -->
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <!-- 商品データを1行ずつ変数に格納し以下に1行ずつ表示させる -->
          <?php foreach($carts as $cart){ ?>
          <!-- n行目 -->
          <tr>
            <!-- 商品画像を表示 -->
            <td><img src="<?php print(IMAGE_PATH . $cart['image']);?>" class="item_image"></td>
            <!-- 商品名を表示 -->
            <td><?php print($cart['name']); ?></td>
            <!-- 商品の値段を3桁のカンマ区切りで表示 -->
            <td><?php print(number_format($cart['price'])); ?>円</td>
            <td>
              <!-- 指定商品の数量を変更するフォーム -->
              <form method="post" action="cart_change_amount.php">
                <!-- 数量を変更するフォーム部品 -->
                <input type="number" name="amount" value="<?php print($cart['amount']); ?>">
                個
                <!-- 数量変更処理へ進むボタン -->
                <input type="submit" value="変更" class="btn btn-secondary">
                <!-- 数量変更処理に必要な隠しデータ。商品カートid -->
                <input type="hidden" name="cart_id" value="<?php print($cart['cart_id']); ?>">
              </form>
            </td>
            <!-- 商品の合計金額を表示 -->
            <td><?php print(number_format($cart['price'] * $cart['amount'])); ?>円</td>
            <td>
              <!-- 指定商品データをカート内から削除するフォーム -->
              <form method="post" action="cart_delete_cart.php">
                <!-- 削除処理へ進むボタン -->
                <input type="submit" value="削除" class="btn btn-danger delete">
                <!-- 削除処理に必要な隠しデータ。商品カートid -->
                <input type="hidden" name="cart_id" value="<?php print($cart['cart_id']); ?>">
              </form>

            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <!-- カート内の商品全ての金額の合計を表示 -->
      <p class="text-right">合計金額: <?php print number_format($total_price); ?>円</p>
      <!-- カート内商品を購入するフォーム -->
      <form method="post" action="finish.php">
        <!-- 購入処理へ進むボタン -->
        <input class="btn btn-block btn-primary" type="submit" value="購入する">
      </form>
    <!-- 商品がカートに入っていない -->
    <?php } else { ?>
      <!-- テキストを表示 -->
      <p>カートに商品はありません。</p>
    <?php } ?> 
  </div>
  <script>
    // 商品を削除する時、確認用のポップアップを表示させる
    $('.delete').on('click', () => confirm('本当に削除しますか？'))
  </script>
</body>
</html>
