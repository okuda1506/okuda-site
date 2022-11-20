<?php 
session_start();

//クリックジャッキング対策
header('X-FRAME-OPTIONS: DENY');

if(!isset($_SESSION['send']) || empty($_SESSION["token"]) || !isset($_SESSION["token"])) {
  header('Location: index.php');
  exit();
}

$_SESSION = array();
session_destroy(); 
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TAKUYA OKUDA PORTFOLIO</title>
  <meta name="description" content="TAKUYA OKUDA PORTFOLIO">

  <!-- CSS -->
  <link rel = "stylesheet" href = "https://unpkg.com/ress/dist/ress.min.css">
  <link rel="icon" href="img/portfolio/favicon.ico">
  <link rel="stylesheet" href="css/contact.css">
</head>
<body>
  <div class="cover">
    <!-- header -->
    <header class="header">
      <div class="inner">
        <h1 class="header-logo">
          <a href="index.php">TAKUYA OKUDA</a>
        </h1>
        <div class="nav-wrapper">
          <nav class="header-nav">
            <ul class="nav-list">
              <li class="nav-item"><a href="index.php#works">WORKS</a></li>
              <li class="nav-item"><a href="index.php#skill">SKILLS</a></li>
              <li class="nav-item"><a href="index.php#about">ABOUT</a></li>
              <li class="nav-item"><a href="index.php#contact">CONTACT</a></li>
            </ul>
          </nav>
        </div>

        <div class="burger-btn"><!-- ハンバーガーボタン -->
          <span class="bar bar_top"></span>
          <span class="bar bar_mid"></span>
          <span class="bar bar_bottom"></span>
        </div>
      </div>
    </header>
    <!-- /header -->

    <!-- CONTACT -->
    <div class="contact">
      <h2 class="title">CONTACT</h2>
      <p class="thanks">お問い合わせを受け付けました。ありがとうございます。</p>
      <input type="button" class="button" value="トップ" onClick="location.href='index.php'">
    </div>
    <!-- /CONTACT -->
    
    <!-- FOOTER -->
    <footer class="footer">
      <div class="copyright">&copy;2021 TAKUYA OKUDA. All Rights Reserved.</div>
    </footer>
    <!-- /FOOTER -->
  </div>
  
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    
  <script script src="./js/script.js"></script>
  <!-- /jQuery -->
</body>
</html>