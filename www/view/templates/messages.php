<!-- get_errors()配列に中身があれば、一つずつ$error変数に格納し表示させる -->
<?php foreach(get_errors() as $error){ ?>
  <!-- エラーメッセージを表示 -->
  <p class="alert alert-danger"><span><?php print $error; ?></span></p>
<?php } ?>
<!-- get_message()配列に中身があれば、一つずつ$messag変数に格納し表示させる -->
<?php foreach(get_messages() as $message){ ?>
  <!-- メッセージを表示 -->
  <p class="alert alert-success"><span><?php print $message; ?></span></p>
<?php } ?>
