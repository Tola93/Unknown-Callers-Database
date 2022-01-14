<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'containers/head.php'; ?>
</head>

<body>
  <div class="container">
    <?php
      include './containers/navbar.php';
    ?>
  <?php
  if(isset($_SESSION['user_id']) && $_SESSION['name'] != "") {
    echo '<h1>Sikeres bejelentkezés!. Üdvözlünk '.$_SESSION['name'].'!</h1>';
  } else {
    echo '<h1>Nem vagy bejelentkezve!</h1>';
  }
  ?>
  <?php
    include './containers/footer.php';
  ?>
  </div>
</body>
</html>
