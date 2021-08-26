<?php

  include("database_connection.php");

  $errors = array();

  $sql =
  "SELECT
  (incoming_calls.call_id) AS `call_id`,
  (calling_numbers.calling_code) AS `calling_code1`, (calling_numbers.prefix) AS `prefix1`, (calling_numbers.numbers) AS `numbers1`,
  (incoming_calls.date_time) AS `date_time`, (incoming_calls.state) AS `state`, (incoming_calls.notes) AS `notes`,
  (called_numbers.calling_code) AS `calling_code2`, (called_numbers.prefix) AS `prefix2`, (called_numbers.numbers) AS `numbers2`
  FROM `incoming_calls`
  JOIN `calling_numbers`
  ON (incoming_calls.calling_number_id = calling_numbers.calling_number_id)
  JOIN `called_numbers`
  ON (incoming_calls.called_number_id = called_numbers.called_number_id)";
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $sql = "SELECT `numbers` FROM `calling_numbers` ORDER BY `prefix`";
  $calling_tele_in = $db->prepare($sql);
  $calling_tele_in->execute();

  $sql = "SELECT `numbers` FROM `called_numbers` ORDER BY `prefix`";
  $called_tele_in = $db->prepare($sql);
  $called_tele_in->execute();

  if (isset($_POST['save_call'])) {

    $calling_tele_in = !empty($_POST['calling_tele_in']) ? trim($_POST['calling_tele_in']) : null;
    $called_tele_in = !empty($_POST['called_tele_in']) ? trim($_POST['called_tele_in']) : null;
    $call_date_in = !empty($_POST['call_date_in']) ? trim($_POST['call_date_in']) : null;
    $call_state_in = !empty($_POST['call_state_in']) ? trim($_POST['call_state_in']) : null;
    $notes_in = !empty($_POST['notes_in']) ? trim($_POST['notes_in']) : null;

    if (empty($calling_tele_in)) { array_push($errors, "calling_tele_in hiba"); }
    if (empty($called_tele_in)) { array_push($errors, "called_tele_in hiba"); }
    if (empty($call_date_in)) { array_push($errors, "call_date_in hiba"); }
    if (empty($call_state_in)) { array_push($errors, "call_state_in hiba"); }

    if (count($errors) == 0) {

      $sql = "SELECT `calling_number_id` FROM `calling_numbers` WHERE `numbers` = :calling_tele_in";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':calling_tele_in', $calling_tele_in);
      $stmt->execute();
      $calling_number_id = $stmt->fetchColumn();

      $sql = "SELECT `called_number_id` FROM `called_numbers` WHERE `numbers` = :called_tele_in";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':called_tele_in', $called_tele_in);
      $stmt->execute();
      $called_number_id = $stmt->fetchColumn();

      $sql = "INSERT INTO `incoming_calls` (`calling_number_id`, `called_number_id`, `date_time`, `state`, `notes`) VALUES (?, ?, ?, ?, ?)";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(1, $calling_number_id);
      $stmt->bindParam(2, $called_number_id);
      $stmt->bindParam(3, $call_date_in);
      $stmt->bindParam(4, $call_state_in);
      $stmt->bindParam(5, $notes_in);
      $stmt->execute();
      header('Location: incoming_calls.php');
      exit;
    }
    header('Location: incoming_calls.php');
    exit;
  }
  
?>
