<!-- htmlのバージョン宣言 -->
<!DOCTYPE html>
<!-- このページのhtml要素は日本語を適応する -->
<html lang="ja">
<!-- ここからこのページの概要 -->
<head>
  <!-- ページ概要テンプレートファイル読み込み -->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <!-- このページのタイトル -->
  <title>商品一覧</title>
  <!-- このページ用のスタイルシート読み込み -->
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<!-- ここからこのページのウェブサイトで表示させる部分 -->
<body>
  <!-- ヘッダーテンプレートファイル読み込み -->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  
  <!-- ヘッダー以外のコンテンツをcontainerクラスで囲む -->
  <div class="container">
    <!-- テキストを見出しで表示 -->
    <h1>商品一覧</h1>
    <!-- 何かメッセージがあれば表示する -->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <!-- $items変数に格納された商品データを１行分ずつ$item変数に格納し、表示させる -->
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <!-- 商品名を表示 -->
              <?php print($item['name']); ?>
            </div>
            <figure class="card-body">
              <!-- 商品画像を表示 -->
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <!-- 金額を表示 -->
                <?php print(number_format($item['price'])); ?>円
                <!-- 在庫がある場合以下を表示 -->
                <?php if($item['stock'] > 0){ ?>
                  <!-- カートに商品を追加するフォーム -->
                  <form action="index_add_cart.php" method="post">
                    <!-- カートに商品を追加する処理へ進むボタン -->
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <!-- 処理に必要な隠し項目。商品id -->
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                  </form>
                <!-- 在庫がない場合以下を表示 -->
                <?php } else { ?>
                  <!-- テキストを表示 -->
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
  
</body>
</html>
