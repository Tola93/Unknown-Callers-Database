<?php

  include("./../model/database_connection.php");

  $errors = array();

  $isIncomingCaller=False;
  $isUserTelephone=False;
  $isCall=False;

  if (!empty($_SESSION['is_logged_in'])) {
    $user_id = $_SESSION['user_id'];
  } else {
    $user_id = 0;
  }

  if (strpos($_SERVER['REQUEST_URI'], "calling_number_id") !== false) {

    $sql = "SELECT `calling_number_id`,`calling_code`,`prefix`,`numbers` FROM `calling_numbers` WHERE `calling_number_id`=:calling_number_id AND `user_id`=:user_id ORDER BY `prefix`";
    $stmt = $db->prepare($sql);
    $calling_number_id =  preg_replace("/[^a-zA-Z0-9-]/","",$_GET['calling_number_id']);
    $stmt->bindValue(':calling_number_id', htmlspecialchars($calling_number_id));
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $isIncomingCaller=True;

  }

  if (strpos($_SERVER['REQUEST_URI'], "called_number_id") !== false) {

    $sql = "SELECT `called_number_id`,`calling_code`,`prefix`,`numbers` FROM `called_numbers` WHERE `called_number_id`=:called_number_id AND `user_id`=:user_id ORDER BY `prefix`";
    $stmt = $db->prepare($sql);
    $called_number_id =  preg_replace("/[^a-zA-Z0-9-]/","",$_GET['called_number_id']);
    $stmt->bindValue(':called_number_id', htmlspecialchars($called_number_id));
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $isUserTelephone=True;

  }

  if (strpos($_SERVER['REQUEST_URI'], "call_id") !== false) {

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
    WHERE `call_id`=:call_id
    AND (incoming_calls.user_id)=:user_id";
    $stmt = $db->prepare($sql);
    $call_id =  preg_replace("/[^a-zA-Z0-9-]/","",$_GET['call_id']);
    $stmt->bindValue(':call_id', htmlspecialchars($call_id));
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $isCall=True;

  }

  if (isset($_POST['removeIncomingTelephoneNumber'])) {
    $calling_number_id = !empty($_POST['calling_number_id']) ? trim($_POST['calling_number_id']) : null;

    if (count($errors) == 0) {
      $sql = "DELETE FROM `calling_numbers` WHERE `calling_number_id`=:calling_number_id AND `user_id`=:user_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':calling_number_id', $calling_number_id);
      $stmt->bindValue(':user_id', $user_id);
      $result = $stmt->execute();
    }
        header('Location: ./../view/calling_numbers.php');
  }

  if (isset($_POST['removeUserTelephoneNumber'])) {
    $called_number_id = !empty($_POST['called_number_id']) ? trim($_POST['called_number_id']) : null;

    if (count($errors) == 0) {
      $sql = "DELETE FROM `called_numbers` WHERE `called_number_id`=:called_number_id AND `user_id`=:user_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':called_number_id', $called_number_id);
      $stmt->bindValue(':user_id', $user_id);
      $result = $stmt->execute();
    }
    header('Location: ./../view/called_numbers.php');
  }

  if (isset($_POST['removeIncomingCall'])) {
    $call_id = !empty($_POST['call_id']) ? trim($_POST['call_id']) : null;

    if (count($errors) == 0) {
      $sql = "DELETE FROM `incoming_calls` WHERE `call_id`=:call_id AND `user_id`=:user_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':call_id', $call_id);
      $stmt->bindValue(':user_id', $user_id);
      $result = $stmt->execute();
    }
    header('Location: ./../view/incoming_calls.php');
  }

?>
