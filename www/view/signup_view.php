<!-- htmlのバージョン宣言 -->
<!DOCTYPE html>
<!-- このhtml要素は日本語を使用する -->
<html lang="ja">
<!-- ここからこのページの概要 -->
<head>
  <!-- ページ概要テンプレートファイル読み込み -->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <!-- このページのタイトル -->
  <title>サインアップ</title>
  <!-- このページのスタイルシート読み込み -->
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'signup.css'); ?>">
</head>
<!-- ここからこのページのブラウザで表示させる部分 -->
<body>
  <!-- ヘッダーコンテンツ読み込み -->
  <?php include VIEW_PATH . 'templates/header.php'; ?>
  <!-- ヘッダー以外のコンテンツをcontainerクラスで囲む -->
  <div class="container">
    <!-- テキストを見出しで表示 -->
    <h1>ユーザー登録</h1>

    <!-- 何かメッセージがあれば表示させる -->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <!-- 新規ユーザ登録フォーム -->
    <form method="post" action="signup_process.php" class="signup_form mx-auto">
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名を表す -->
        <label for="name">名前: </label>
        <!-- 登録する名前を入力できる -->
        <input type="text" name="name" id="name" class="form-control">
      </div>
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名を表す -->
        <label for="password">パスワード: </label>
        <!-- 登録するパスワードを入力できる -->
        <input type="password" name="password" id="password" class="form-control">
      </div>
      <!-- フォーム部品をform-groupクラスで囲む -->
      <div class="form-group">
        <!-- フォーム部品の項目名を表す -->
        <label for="password_confirmation">パスワード（確認用）: </label>
        <!-- 登録するパスワードを再度入力する -->
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
      </div>
      <!-- 新規ユーザ登録処理へ進むボタン（signup_process.phpへ移動し、新規ユーザ登録処理をする）-->
      <input type="submit" value="登録" class="btn btn-primary">
    </form>
  </div>
</body>
</html>
