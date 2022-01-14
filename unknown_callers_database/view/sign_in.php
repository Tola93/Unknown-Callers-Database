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
  <?php if (isset($_SESSION['is_logged_in']) == ""): ?>
  <div class="signInContainer"> <!-- Bejelentkezés-->
    <div id="signInHeader">
        <div id="signInTitle">Bejelentkezés</div><br>
        <div id="signInArea">
          <form action="" method="post"> <!-- Form, action PHP-hoz fog kelleni -->
            <div id="signInText"><label>Felhasználónév: </label><br>
              <input type="text" name="username" id="username" value="" autocomplete="off" /></div><br>
            <div id="signInText"><label>Jelszó: </label><br>
              <input type="password" name="password" id="password" value="" autocomplete="off" /></div><br>
            <div id="signInText"><input type="submit" name="submitBtnLogin" id="submitBtnLogin" value="Bejelentkezés" /><span class="loginMsg"></span></div><br>
          </form>
       </div>
    </div>
  </div>
  <div>
    <a href="registration.php">Regisztráció</a></p>
  </div>
  <?php endif; ?>
  <?php
    include './containers/footer.php';
  ?>
  </div>
</body>
</html>
