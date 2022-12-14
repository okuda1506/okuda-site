<?php 
session_start();

//クリックジャッキング対策
header('X-FRAME-OPTIONS: DENY');

require('dbconnect.php');

if(empty($_REQUEST['id'])) {          //$_REQUEST['id'] ⇒ postsテーブルのid
  header('Location: index.php');
  exit();
}

// 投稿を取得する
$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
$posts->execute(array($_REQUEST['id']));

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
    <p>&laquo;<a href="index.php">一覧に戻る</a></p>
    <?php if($post = $posts->fetch()): ?>
      <div class="msg">
        <img src="member_picture/<?php echo h($post['picture']); ?>" width="48" height="48" alt="<?php echo h($post['name']); ?>">
        <p><?php echo makeLink(h($post['message'])); ?><span class="name"> (<?php echo h($post['name']); ?>) </span></p>
        <p class="day"><?php echo h($post['created']); ?></p>
      </div>
    <?php else: ?>
      <p>その投稿は削除されたか、URLが間違えています</p>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
