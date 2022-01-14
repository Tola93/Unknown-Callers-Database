<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'containers/head.php'; ?>
</head>

<body>
  <div class="container">
    <?php
      include './containers/navbar.php';
      include './../controller/registration_controller.php';
    ?>
  <?php if (isset($_SESSION['is_logged_in']) == ""): ?>
    <div class="registrationContainer"> <!-- Regisztrációs oldal-->
      <div id="registrationHeader">
         <div id="registrationTitle">Regisztráció</div><br>
         <div id="registrationArea">
            <form method="post" action="registration"> <!-- Form, action PHP-hoz fog kelleni -->
              <?php //include('registration_controller.php'); ?>
              <div id="input-group"><label>Felhasználónév: </label><br>
                <input type="text" name="username"></div><br>
              <div id="input-group"><label>Jelszó: </label><br>
                <input type="password" name="password" id="password" value="" autocomplete="off" /></div><br>
              <div id="signInText"><label>Jelszó megerősítése: </label><br>
                <input type="passwordAgain" name="passwordAgain" id="passwordAgain" value="" autocomplete="off" /></div><br>
              <div id="input-group"><button type="submit" name="reg_user">Regisztráció</button></div><br>
               <input type="button" onclick="window.location.href='sign_in.php'" value='Vissza' />
            </form>
         </div>
      </div>
    </div>
  <?php endif; ?>
  <?php
    include './containers/footer.php';
  ?>
  </div>
</body>
</html>
