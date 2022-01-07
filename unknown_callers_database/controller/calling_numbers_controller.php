<?php

  include("./../model/database_connection.php");

  $errors = array();

  if (!empty($_SESSION['is_logged_in'])) {
    $user_id = $_SESSION['user_id'];
  } else {
    $user_id = 0;
  }

  $sql = "SELECT * FROM `calling_numbers` WHERE `user_id`=:user_id ORDER BY `numbers`";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();

  $sql = "SELECT `calling_code` FROM `country_calling_codes` ORDER BY `calling_code`";
  $calling_code_in = $db->prepare($sql);
  $calling_code_in->execute();

  $sql = "SELECT `prefix` FROM `prefixes_hu` ORDER BY `prefix`";
  $prefix_in = $db->prepare($sql);
  $prefix_in->execute();

  if (isset($_POST['save_phone'])) {

    $calling_code = !empty($_POST['calling_code_in']) ? trim($_POST['calling_code_in']) : null;
    $prefix = !empty($_POST['prefix_in']) ? trim($_POST['prefix_in']) : null;
    $numbers = !empty($_POST['numbers_in']) ? trim($_POST['numbers_in']) : null;

    if (empty($calling_code)) { array_push($errors, "calling_code hiba"); }
    if (empty($prefix)) { array_push($errors, "prefix hiba"); }
    if (empty($numbers)) { array_push($errors, "numbers hiba"); }

    $sql = "SELECT * FROM `calling_numbers` WHERE `calling_code`=:calling_code AND `prefix`=:prefix AND `numbers`=:numbers AND `user_id`=:user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':calling_code', $calling_code);
    $stmt->bindValue(':prefix', $prefix);
    $stmt->bindValue(':numbers', $numbers);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $phone = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($phone) {
      array_push($errors, "Ez a telefonszám már létezik");
    }

    if (count($errors) == 0) {

      $sql = "INSERT INTO `calling_numbers` (`calling_code`, `prefix`, `numbers`, `user_id`) VALUES (?, ?, ?, ?)";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(1, $calling_code);
      $stmt->bindParam(2, $prefix);
      $stmt->bindParam(3, $numbers);
      $stmt->bindParam(4, $user_id);
      $result = $stmt->execute();
      header('Location: ./../view/calling_numbers.php');
      exit;
    }
    header('Location: ./../view/calling_numbers.php');
    exit;
  }

?>
