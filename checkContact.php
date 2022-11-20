<?php 
session_start();

//クリックジャッキング対策
header('X-FRAME-OPTIONS: DENY');

if(!isset($_SESSION['send'])) {
  header('Location: index.php');
  exit();
}

// PHPMailerの使用
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\src\SMTP;
use PHPMailer\PHPMailer\src\Exception;
require 'vendor/autoload.php';
$mail = new PHPMailer(true);

if(isset($_POST['send']) && isset($_POST["token"])) {
  if(empty($_SESSION["token"]) || !isset($_SESSION["token"]) || !hash_equals($_SESSION["token"], $_POST["token"])) {
    // トークン異常
    $_SESSION['error'] = '不正な処理が行われました。お手数ですが再度やり直してください。';
    header('Location: errorMessage.php');
    exit();
  }
  require_once('php/sendMail.php');
  header('Location: thanks.php');
  exit();
}

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES);
}
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
      <p class="contact-text">お問い合わせ内容をご確認ください。</p>
      <form action="" method="post" class="contactForm">
      <input type="hidden" name="token" value="<?php echo $_SESSION["token"]; ?>">
        <table>
          <tbody>
            <tr>
              <th>お名前</th>
              <td><p class="check-text"><?php echo h($_SESSION['send']['name']); ?></p></td>
            </tr>
            <tr>
              <th>メールアドレス</th>
              <td><p class="check-text"><?php echo h($_SESSION['send']['email']); ?></p></td>
            </tr>
            <tr>
              <th>お問い合わせ内容</th>
              <td><p class="check-text"><?php echo nl2br(h($_SESSION['send']['message'])); ?></p></td>
            </tr>
          </tbody>
        </table>
          <div class="btnPosition">
            <input type="button" class="back" name="back" value="戻る" onClick="location.href='index.php?action=rewrite#contact'">
            <input type="submit" class="send" name="send" value="送信">
          </div>
      </form>
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