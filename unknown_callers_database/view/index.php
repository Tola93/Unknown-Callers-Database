<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'containers/head.php'; ?>
</head>

<body>
  <div class="container">
    <?php
      include './containers/navbar.php';
      include './../controller/sign_in_controller.php';
    ?>
    FŐOLDAL
  <?php
  if(isset($_SESSION['user_id']) && $_SESSION['name'] != "") {
    echo '<h1>Üdvözlünk '.$_SESSION['name'].'!</h1>';
  } else {
    echo '<h1>Nem vagy bejelentkezve!</h1>';
  }
  ?>
  <?php if (isset($_SESSION['is_logged_in']) == ""): ?>
  <div class="signInContainer"> <!-- Bejelentkezés-->
  <div id="signInHeader">
     <div id="signInTitle">Bejelentkezés</div>
     <div id="signInArea">
        <form action="" method="post"> <!-- Form, action PHP-hoz fog kelleni -->
           <div id="signInText"><label>Felhasználónév: </label><br>
             <input type="text" name="username" id="username" value="" autocomplete="off" /></div>
           <div id="signInText"><label>Jelszó: </label><br>
             <input type="password" name="password" id="password" value="" autocomplete="off" /></div><br>
           <div id="signInText"><input type="submit" name="submitBtnLogin" id="submitBtnLogin" value="Bejelentkezés" /><span class="loginMsg"></span></div>
        </form>
     </div>
  </div>
  </div>
<?php endif; ?>
  </div>
</body>
</html>
