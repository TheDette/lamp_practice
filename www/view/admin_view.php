<!-- htmlのバージョン宣言 -->
<!DOCTYPE html>
<!-- このページのhtml要素は日本語が適応される -->
<html lang="ja">
<!-- ここからこのページの概要 -->
<head>
  <!-- ページ概要テンプレートファイル読み込み -->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <!-- このページのタイトル -->
  <title>商品管理</title>
  <!-- このページ用のスタイルシート読み込み -->
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<!-- ここからこのページのブラウザを表示させる部分 -->
<body>
  <!-- ヘッダーコンテンツ読み込み -->
  <?php 
  include VIEW_PATH . 'templates/header_logined.php'; 
  ?>

  <!-- ヘッダー以外のコンテンツをcontainerクラスで囲む -->
  <div class="container">
    <!-- テキストを見出しで表示 -->
    <h1>商品管理</h1>

    <!-- 何かメッセージがあれば表示させる -->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <!-- 商品を追加するフォーム -->
    <form 
      method="post" 
      action="admin_insert_item.php" 
      enctype="multipart/form-data"
      class="add_item_form col-md-6">
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名 -->
        <label for="name">名前: </label>
        <!-- 商品名を入力するテキストフォーム -->
        <input class="form-control" type="text" name="name" id="name">
      </div>
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名 -->
        <label for="price">価格: </label>
        <!-- 値段を入力するナンバーフォーム -->
        <input class="form-control" type="number" name="price" id="price">
      </div>
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名 -->
        <label for="stock">在庫数: </label>
        <!-- 在庫数を入力するナンバーフォーム -->
        <input class="form-control" type="number" name="stock" id="stock">
      </div>
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名 -->
        <label for="image">商品画像: </label>
        <!-- 商品画像を挿入するフォーム -->
        <input type="file" name="image" id="image">
      </div>
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名 -->
        <label for="status">ステータス: </label>
        <!-- オプション要素でステータスを選択するフォーム -->
        <select class="form-control" name="status" id="status">
          <!-- 商品を公開する -->
          <option value="open">公開</option>
          <!-- 商品を非公開にする -->
          <option value="close">非公開</option>
        </select>
      </div>
      
      <!-- 商品追加処理へ進むボタン（admin_insert_item.phpへ移動し、商品追加処理をする） --> 
      <input type="submit" value="商品追加" class="btn btn-primary">
    </form>

    <!-- DBから取得した商品データがあれば以下に表示させる -->
    <?php if(count($items) > 0){ ?>
      <!-- 商品データをテーブル要素で表示させる -->
      <table class="table table-bordered text-center">
        <!-- 商品データの項目名のまとまり -->
        <thead class="thead-light">
          <!-- テーブルの1行目 -->
          <tr>
            <!-- 項目名 -->
            <th>商品画像</th>
            <!-- 項目名 -->
            <th>商品名</th>
            <!-- 項目名 -->
            <th>価格</th>
            <!-- 項目名 -->
            <th>在庫数</th>
            <!-- 項目名 -->
            <th>操作</th>
          </tr>
        </thead>
        <!-- 商品データのまとまり -->
        <tbody>
          <!-- 商品データが配列で格納されている$items変数から1行ずつデータを取り出し表示する -->
          <?php foreach($items as $item){ ?>
          <!-- ステータスが「公開」の場合、クラスを指定しない。「非公開」の場合close_itemクラスを指定する -->
          <tr class="<?php print(is_open($item) ? '' : 'close_item'); ?>">
            <!-- 商品画像を表示 -->
            <td><img src="<?php print(IMAGE_PATH . $item['image']);?>" class="item_image"></td>
            <!-- 商品名を表示 -->
            <td><?php print($item['name']); ?></td>
            <!-- 金額を表示。number_format関数で数値を3桁のカンマ区切りにする -->
            <td><?php print(number_format($item['price'])); ?>円</td>
            <td>

              <!-- 在庫数を変更するフォーム -->
              <form method="post" action="admin_change_stock.php">
                <!-- フォーム部品をクラスで囲む -->
                <div class="form-group">
                  <!-- 在庫数を表示。在庫数を変更するフォーム部品 -->
                  <!-- sqlインジェクション確認のためあえてtext -->
                  <input  type="text" name="stock" value="<?php print($item['stock']); ?>">
                  個
                </div>
                <!-- 在庫数変更処理へ進むボタン。（admin_change_stock.phpへ移動し在庫数変更処理を行う） -->
                <input type="submit" value="変更" class="btn btn-secondary">
                <!-- 在庫数変更DB処理に必要な隠しデータ商品id -->
                <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
              </form>
            </td>
            <td>

              <!-- ステータスを変更するフォーム -->
              <form method="post" action="admin_change_status.php" class="operation">
                <!-- ステータスが「公開」である -->
                <?php if(is_open($item) === true){ ?>
                  <!-- ボタンの表示は「公開 → 非公開」。ステータス変更処理へ進む -->
                  <input type="submit" value="公開 → 非公開" class="btn btn-secondary">
                  <!-- ステータス変更DB処理に必要な隠しデータ。値はcloseを送る。 -->
                  <input type="hidden" name="changes_to" value="close">
                <!-- ステータスが「非公開」である -->
                <?php } else { ?>
                  <!-- ボタンの表示は「非公開 → 公開」。ステータス変更処理へ進む -->
                  <input type="submit" value="非公開 → 公開" class="btn btn-secondary">
                  <!-- ステータス変更DB処理に必要な隠しデータ。値はopenを送る。 -->
                  <input type="hidden" name="changes_to" value="open">
                <?php } ?>
                <!-- 在庫数変更DB処理に必要な隠しデータ商品id -->
                <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
              </form>

              <!-- 商品データを削除するフォーム --> 
              <form method="post" action="admin_delete_item.php">
                <!-- 商品データ削除処理へ進む。（admin_delete_item.phpへ進み商品データ削除処理を行う） -->
                <input type="submit" value="削除" class="btn btn-danger delete">
                <!-- 在庫数変更DB処理に必要な隠しデータ商品id -->
                <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
              </form>

            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <!-- DBから取得する商品データが無い -->
    <?php } else { ?>
      <!-- テキストを表示 -->
      <p>商品はありません。</p>
    <?php } ?> 
  </div>
  <script>
    // 「削除」ボタンを押した時に確認用のポップアップを表示させる
    $('.delete').on('click', () => confirm('本当に削除しますか？'))
  </script>
</body>
</html>
