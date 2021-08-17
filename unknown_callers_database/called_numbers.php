<?php

  include("database_connection.php");

  $errors = array();

  if (isset($_POST['save_phone'])) {

    $calling_code = !empty($_POST['calling_code_in']) ? trim($_POST['calling_code_in']) : null;
    $prefix = !empty($_POST['prefix_in']) ? trim($_POST['prefix_in']) : null;
    $numbers = !empty($_POST['numbers_in']) ? trim($_POST['numbers_in']) : null;

    if (empty($calling_code)) { array_push($errors, "calling_code hiba"); }
    if (empty($prefix)) { array_push($errors, "prefix hiba"); }
    if (empty($numbers)) { array_push($errors, "numbers hiba"); }

    $sql = "SELECT * FROM `called_numbers` WHERE `calling_code`=:calling_code AND `prefix`=:prefix AND `numbers`=:numbers";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':calling_code', $calling_code);
    $stmt->bindValue(':prefix', $prefix);
    $stmt->bindValue(':numbers', $numbers);
    $stmt->execute();
    $phone = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($phone) {
      array_push($errors, "Ez a telefonszám már létezik");
    }

    if (count($errors) == 0) {

      $sql = "INSERT INTO `called_numbers` (`calling_code`, `prefix`, `numbers`) VALUES (?, ?, ?);";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(1, $calling_code);
      $stmt->bindParam(2, $prefix);
      $stmt->bindParam(3, $numbers);
      $result = $stmt->execute();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="navbar.css">
  <script type="text/javascript" src="jquery/js/jquery-3.6.0.min"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container">
    <?php include 'navbar.php'; ?>
    <?php
      $sql = "SELECT * FROM `called_numbers` ORDER BY `numbers`";
      $stmt = $db->prepare($sql);
      $stmt->execute();

      echo "<table class='table table-striped'>";
      echo "<tr><td> " . 'calling_code' . "</td><td>" . 'prefix' . "</td><td>" . 'numbers' . "</td></tr>";
      while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr class='tele_rows' id='$result[called_number_id]' name='$result[called_number_id]'><td>" . $result['calling_code'] . "</td><td>" . $result['prefix'] . "</td><td>" . $result['numbers'] . "</td><td><a href='edit.php?called_number_id=$result[called_number_id]'>Módosítás</a></td><td><a href='delete.php?called_number_id=$result[called_number_id]'>Törlés</a></td></tr>";
      }
      echo "</table>";
    ?>
    <form method="post" action="called_numbers.php">
      <div>
        calling_code:
        <select name="calling_code_in">
          <option value="">--- Select ---</option>
          <?php
            $sql = "SELECT `calling_code` FROM `country_calling_codes` ORDER BY `calling_code`";
            $stmt = $db->prepare($sql);
            $stmt->execute();
          ?>
          <?php
            while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
          ?>
          <option value="<?php echo $row_list['calling_code']; ?>"><?php echo $row_list['calling_code']; ?></option>
          <?php
            }
          ?>
        </select>
      </div>
      <div>
        prefix:
        <select name="prefix_in" >
          <option value="">--- Select ---</option>
          <?php
            $sql = "SELECT `prefix` FROM `prefixes_hu` ORDER BY `prefix`";
            $stmt = $db->prepare($sql);
            $stmt->execute();
          ?>
          <?php
            while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
          ?>
          <option value="<?php echo $row_list['prefix']; ?>"><?php echo $row_list['prefix']; ?></option>
          <?php
            }
          ?>
        </select>
      </div>
      <div>
        numbers:
        <input name="numbers_in" type="text">
      </div>
      <div>
        <button type="submit" name="save_phone">Telefonszám mentése</button>
      </div>
    </form>
  </div>
</body>
</html>
