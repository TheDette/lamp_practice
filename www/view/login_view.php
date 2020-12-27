<!-- htmlのバージョン宣言 -->
<!DOCTYPE html>
<!-- このページのhtml要素は日本語を使用する -->
<html lang="ja">
<!-- ここからこのページの概要 -->
<head>
  <!-- ページ概要テンプレートファイル読み込み -->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <!-- このページのタイトル -->
  <title>ログイン</title>
  <!-- このページのスタイルシートを読み込み -->
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'login.css'); ?>">
</head>
<!-- ここからこのページのブラウザで表示させる部分 -->
<body>
  <!-- ヘッダーコンテンツ読み込み -->
  <?php include VIEW_PATH . 'templates/header.php'; ?>
  <!-- ヘッダー以外のコンテンツをcontainerクラスで囲む -->
  <div class="container">
    <!-- テキストを見出しで表示 -->
    <h1>ログイン</h1>

    <!-- 何かメッセージがあれば表示させる -->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <!-- ログイン処理を行うフォーム -->
    <form method="post" action="login_process.php" class="login_form mx-auto">
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名を表す -->
        <label for="name">名前: </label>
        <!-- ログインに必要な名前を入力できる -->
        <input type="text" name="name" id="name" class="form-control">
      </div>
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名を表す -->
        <label for="password">パスワード: </label>
        <!-- ログインに必要なパスワードを入力できる -->
        <input type="password" name="password" id="password" class="form-control">
      </div>
      <!-- ログイン処理へ進むボタン（login_process.phpへ移動しログイン処理をする) -->
      <input type="submit" value="ログイン" class="btn btn-primary">
    </form>
  </div>
</body>
</html>
