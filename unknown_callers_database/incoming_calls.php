<?php

  include("database_connection.php");

  $errors = array();

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

      $sql = "INSERT INTO `inbound_calls` (`calling_number_id`, `called_number_id`, `date_time`, `state`, `notes`) VALUES (?, ?, ?, ?, ?)";
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
    <div>
      <?php
        $sql =
        "SELECT
        (inbound_calls.call_id) AS `call_id`,
        (calling_numbers.calling_code) AS `calling_code1`, (calling_numbers.prefix) AS `prefix1`, (calling_numbers.numbers) AS `numbers1`,
        (inbound_calls.date_time) AS `date_time`, (inbound_calls.state) AS `state`, (inbound_calls.notes) AS `notes`,
        (called_numbers.calling_code) AS `calling_code2`, (called_numbers.prefix) AS `prefix2`, (called_numbers.numbers) AS `numbers2`
        FROM `inbound_calls`
        JOIN `calling_numbers`
        ON (inbound_calls.calling_number_id = calling_numbers.calling_number_id)
        JOIN `called_numbers`
        ON (inbound_calls.called_number_id = called_numbers.called_number_id)";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        echo "<table class='table table-striped'>";

        echo "<tr><td>" . 'calling_numbers.calling_code' . "</td><td> " . 'calling_numbers.prefix' . "</td><td>" . 'calling_numbers.numbers' . "</td><td>" . 'inbound_calls.date_time' . "</td><td>" . 'called_numbers.calling_code' . "</td><td>" . 'called_numbers.prefix' . "</td><td>" . 'called_numbers.numbers' . "</td><td>" . 'state' . "</td><td>" . 'notes' . "</td></tr>";
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr><td>" . $result['calling_code1'] . "</td><td> " . $result['prefix1'] . "</td><td>" . $result['numbers1'] . "</td><td>" . $result['date_time'] . "</td><td>" . $result['calling_code2'] . "</td><td>" . $result['prefix2'] . "</td><td>" . $result['numbers2'] . "</td><td>" . $result['state'] . "</td><td>" . $result['notes'] . "</td><td><a href='edit.php?call_id=$result[call_id]'>Módosítás</a></td><td><a href='delete.php?call_id=$result[call_id]'>Törlés</a></td></tr>";
        }

        echo "</table>";
      ?>
    </div>
    <div>
      <form method="post" action="incoming_calls.php">
        <div>
          Hívó telefonszám :
          <select name="calling_tele_in">
            <option value="">--- Select ---</option>
            <?php
              $sql = "SELECT `numbers` FROM `calling_numbers` ORDER BY `prefix`";
              $stmt = $db->prepare($sql);
              $stmt->execute();

              $select="unknown_callers";
              if (isset ($select)&&$select!=""){
              $select=$_POST ['NEW'];
              }
            ?>
            <?php
              while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
            ?>
                <option value="<?php echo $row_list['numbers']; ?>"><?php echo $row_list['numbers']; ?></option>
            <?php
              }
            ?>
        </select>
        </div>
        <div>
          Hívott telefonszám :
          <select name="called_tele_in">
            <option value="">--- Select ---</option>
              <?php
                $sql = "SELECT `numbers` FROM `called_numbers` ORDER BY `prefix`";
                $stmt = $db->prepare($sql);
                $stmt->execute();

                $select="unknown_callers";
                if (isset ($select)&&$select!=""){
                  $select=$_POST ['NEW'];
                }
              ?>
              <?php
                while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
              ?>
                  <option value="<?php echo $row_list['numbers']; ?>"><?php echo $row_list['numbers']; ?></option>
              <?php
                }
              ?>
          </select>
        </div>
        <div>
          <div class="container">
            <div class="row">
              <div class='col-sm-6'>
                <div class="form-group">
                  <div class='input-group date'>
                    <input name="call_date_in" type='datetime-local' class="form-control"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div>
          Hívás állapota:
          <select name="call_state_in">
            <option value="missed">Nem fogadva</option>
            <option value="accepted">Fogadva</option>
            <option value="denied">Elutasítva</option>
          </select>
        </div>
        <div>
          Megjegyzés:
          <input name="notes_in" type="text">
        </div>
        <div>
          <button type="submit" name="save_call">Hívás mentése</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
