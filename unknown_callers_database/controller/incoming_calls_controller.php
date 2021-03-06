<?php

  include("./../model/database_connection.php");
  include("./../view/errors.php");

  if (!empty($_SESSION['is_logged_in'])) {
    $user_id = $_SESSION['user_id'];
  } else {
    $user_id = 0;
  }

  $sql =
  "SELECT
  (incoming_calls.call_id) AS `call_id`,
  (calling_numbers.calling_number_id) AS `calling_number_id`,
  (calling_numbers.calling_code) AS `calling_code1`, (calling_numbers.prefix) AS `prefix1`, (calling_numbers.numbers) AS `numbers1`,
  (incoming_calls.date_time) AS `date_time`, (incoming_calls.state) AS `state`, (incoming_calls.notes) AS `notes`,
  (called_numbers.called_number_id) AS `called_number_id`,
  (called_numbers.calling_code) AS `calling_code2`, (called_numbers.prefix) AS `prefix2`, (called_numbers.numbers) AS `numbers2`
  FROM `incoming_calls`
  JOIN `calling_numbers`
  ON (incoming_calls.calling_number_id = calling_numbers.calling_number_id)
  JOIN `called_numbers`
  ON (incoming_calls.called_number_id = called_numbers.called_number_id)
  WHERE (incoming_calls.user_id)=:user_id";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();

  $sql = "SELECT `calling_number_id`,`calling_code`,`prefix`,`numbers` FROM `calling_numbers` WHERE `user_id`=:user_id ORDER BY `prefix`";
  $calling_tele_in = $db->prepare($sql);
  $calling_tele_in->bindValue(':user_id', $user_id);
  $calling_tele_in->execute();

  $sql = "SELECT `called_number_id`,`calling_code`,`prefix`,`numbers` FROM `called_numbers` WHERE `user_id`=:user_id ORDER BY `prefix`";
  $called_tele_in = $db->prepare($sql);
  $called_tele_in->bindValue(':user_id', $user_id);
  $called_tele_in->execute();

  if (isset($_POST['save_call'])) {

    $calling_number_id = !empty($_POST['calling_tele_in']) ? trim($_POST['calling_tele_in']) : null;
    $called_number_id = !empty($_POST['called_tele_in']) ? trim($_POST['called_tele_in']) : null;
    $call_date_in = !empty($_POST['call_date_in']) ? trim($_POST['call_date_in']) : null;
    $call_state_in = !empty($_POST['call_state_in']) ? trim($_POST['call_state_in']) : null;
    $notes_in = !empty($_POST['notes_in']) ? trim($_POST['notes_in']) : null;

    if (empty($calling_tele_in)) { array_push($errors, "calling_tele_in hiba"); }
    if (empty($called_tele_in)) { array_push($errors, "called_tele_in hiba"); }
    if (empty($call_date_in)) { array_push($errors, "call_date_in hiba"); }
    if (empty($call_state_in)) { array_push($errors, "call_state_in hiba"); }

    if (count($errors) == 0) {

      $sql = "INSERT INTO `incoming_calls` (`calling_number_id`, `called_number_id`, `date_time`, `state`, `notes`, `user_id`) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(1, $calling_number_id);
      $stmt->bindParam(2, $called_number_id);
      $stmt->bindParam(3, $call_date_in);
      $stmt->bindParam(4, $call_state_in);
      $stmt->bindParam(5, $notes_in);
      $stmt->bindParam(6, $user_id);
      $stmt->execute();
      header('Location: ./../view/incoming_calls.php');
      exit;
    }
    header('Location: ./../view/incoming_calls.php');
    exit;
  }

?>
