<?php 
require_once('../dbconnect.php');

session_start();       //sessionの初期化（sessionを使うページでは冒頭に必ず入れておく）

if(!empty($_POST)) {
  //エラー項目の確認
  if($_POST['name'] == '') {
    $error['name'] = 'blank';
  }
  if($_POST['email'] == '') {
    $error['email'] = 'blank';
  }
  if(strlen($_POST['password']) < 4) {
    $error['password'] = 'length';
  }
  if($_POST['password'] == '') {
    $error['password'] = 'blank';
  }
  $fileName = $_FILES['image']['name'];          //$_FILES['inputで指定したname']['name'] ⇒ ファイル名
  if(!empty($fileName)) {
    $ext = substr($fileName, -3);               //後ろから3番目を指定して拡張子を取り出す
    if($ext != 'jpg' && $ext != 'gif') {
      $error['image'] = 'type';
    }
  }

  // 重複アカウントのチェック
  if(empty($error)) {
    $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');       // members テーブルの中で、入力値であるアドレスの件数を取得
    $member->execute(array($_POST['email']));
    $record = $member->fetch();                   //上記executeメソッドの結果をfetchで取り出す
    if($record['cnt'] > 0) {
      $error['email'] = 'duplicate'; //duplicate⇒重複
    }

  }

  // アカウント画像をアップロード
  if(empty($error)) { 
    if(empty($_FILES['image']['name'])) {   // プロフィール写真が選択されていない場合
      $image = date('YmdHis') . 'profile_icon.jpg';
      copy('../../images/profile_icon.jpg', '../member_picture/' . $image);
    }else {   // プロフィール写真が選択されている場合 
      $image = date('YmdHis') . $_FILES['image']['name'];  //ファイル名（拡張子付いている）
      move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);    //一時保存フォルダ→「member_picture」フォルダへ移動
    }

    $_SESSION['join'] = $_POST;
    $_SESSION['join']['image'] = $image;
    header('Location: check.php');       // header()関数⇒ 別ページへ移動
    exit();
  }
}
// 書き直し
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite') {
  $_POST = $_SESSION['join'];
  $error['rewrite'] = true;          //「～～画像を改めて指定してください」のメッセージを出すために定義しておく
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="../../style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>会員登録</h1>
  </div>
  <div id="content">
    <p>次のフォームに必要事項をご記入ください</p>
    <form action="" method="post" enctype="multipart/form-data">
    <dl>
      <dt>ニックネーム<span class="required">必須</span></dt>
      <dd>
        <input type="text" name="name" size="35" maxlength="255" value="<?php if(isset($_POST['name'])){echo htmlspecialchars($_POST['name'], ENT_QUOTES); } //入力した値を残す ?>">
        <?php if(isset($error['name']) && $error['name'] == 'blank'): ?>
          <p class="error">* ニックネームを入力してください</p>
        <?php endif; ?>
      </dd>
      <dt>メールアドレス<span class="required">必須</span></dt>
      <dd>
        <input type="text" name="email" size="35" maxlength="255" value="<?php if(isset($_POST['email'])){echo htmlspecialchars($_POST['email'], ENT_QUOTES);} ?>">
        <?php if(isset($error['email']) && $error['email'] == 'blank'):?>
          <p class="error">* メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if(isset($error['email']) && $error['email'] == 'duplicate'):  ?>
          <p class="error">* 指定されたメールアドレスは既に登録されています</p>
        <?php endif;  ?>
      </dd>
      <dt>パスワード<span class="required">必須</span></dt>
      <dd>
        <input type="password" name="password" size="10" maxlength="20" value="<?php if(isset($_POST['password'])){echo htmlspecialchars($_POST['password']);} ?>">
        <?php if(isset($error['password']) && $error['password'] == 'blank'): ?>
          <p class="error">* パスワードを入力してください</p>
        <?php endif; ?>
        <?php if(isset($error['password']) && $error['password'] == 'length'): ?>
          <p class="error">* パスワードは4文字以上で入力してください</p>
        <?php endif; ?>
      </dd>
      <dt>写真など</dt>
      <dd>
        <input type="file" name="image" size="35">
        <?php if(isset($error['image']) && $error['image'] == 'type'): ?>
          <p class="error">* 写真などは「.gif」または「.jpg」を指定してください</p>
        <?php endif; ?>
        
        <?php if(!empty($error)): ?>
          <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
        <?php endif; ?>
      </dd>
    </dl>
    <div><input type="submit" value="入力内容を確認する"></div>
  </form>
  </div>

</div>
</body>
</html>