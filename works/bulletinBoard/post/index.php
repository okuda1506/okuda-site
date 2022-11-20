<?php 
session_start();

//クリックジャッキング対策
header('X-FRAME-OPTIONS: DENY');

require('dbconnect.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {      //idがセッションに記録されている and 最後の行動から1時間以内である
  // ログインしている
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));                   
  $member = $members->fetch();                                 // ログインした人の情報（レコード）を$member変数に代入
}else {
  // ログインしていない
  header('Location: top.html'); 
  exit();
}

// 投稿を記録する
if(!empty($_POST)) {
  if($_POST['message'] != '') {
    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_post_id=?, created=NOW()');
    $message->execute(array(
      $member['id'],
      $_POST['message'],
      $_POST['reply_post_id']           // 値が無い（返信しない）場合は自動的に0がセットされるため、if文等で判定する必要なし
    ));

    header('Location: index.php');
    exit();
  }
}

// 投稿を取得する
$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');  //リレーション(複数テーブルを組み合わせる)したテーブルのレコードを降順で取得

// 返信の場合
if(isset($_REQUEST['res'])) {    // [RE]ボタンが押されたら処理実行
  $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=member_id AND p.id=? ORDER BY p.created DESC');  // ～～～ AND p.id=? ⇒ この条件により1件のレコードだけ取り出せる
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();
  $message = '@' . $table['name'] . ' ' . $table['message'];

}

// htmlspecialcharsのショートカット
function h($value) {
  return htmlspecialchars($value, ENT_QUOTES);
}

//本文内のURLにリンクを設定
function makeLink($value) {
  return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>', $value);   // URLを見つけると「<a href="【URL】">URL</a>」というタグを自動的に作って返す
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ひとこと掲示板</h1>
  </div>
  <div id="content">
    <div style="text-align: right;"><a href="logout.php">ログアウト</a> | <a href="deleteAccountCheck.php">退会</a></div>
    <form action="" method="post">
      <dl>
        <dt><?php echo h($member['name']); ?>さん、メッセージをどうぞ</dt>
        <dd>
          <textarea name="message" cols="50" rows="5"><?php if(isset($message)) {echo h($message);} ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?php echo h($_REQUEST['res']); // 返信先メッセージのidを記録しておくため ?>">
        </dd>
      </dl>
      <div>
        <input type="submit" value="投稿する">
      </div>
    </form>

    <?php foreach($posts as $post): ?>
      <div class="msg">
        <img src="member_picture/<?php echo h($post['picture']); ?>" width="48" height="48" alt="<?php echo h($post['name']); ?>">
        <p><?php echo makeLink(h($post['message'])); ?><span class="name"> (<?php echo h($post['name']); ?>) </span>[<a href="index.php?res=<?php echo h($post['id']); ?>">Re]</a></p>
        <p class="day">
          <a href="view.php?id=<?php echo h($post['id']); ?>"><?php echo h($post['created']); ?></a>
          <?php if($post['reply_post_id'] > 0): ?>
            <a href="view.php?id=<?php echo h($post['reply_post_id']); ?>">返信元のメッセージ</a>
          <?php endif; ?>
          <?php if($_SESSION['id'] == $post['member_id']):   //$_SESSION['id'] ⇒ ログインしている人のID、$post['member_id'] ⇒ 投稿者ID、一致 ⇒ 本人の投稿 ?>
            [<a href="deleteMessage.php?id=<?php  echo h($post['id']); ?>" style="color:#F33;">削除</a>]
          <?php endif; ?>
        </p>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
