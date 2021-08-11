<?php

  include("database_connection.php");

  $errors = array();

  $isIncomingCaller=False;
  $isUserTelephone=False;
  $isCall=False;

  if (strpos($_SERVER['REQUEST_URI'], "calling_number_id") !== false) {

    $sql = "SELECT * FROM `calling_numbers` WHERE `calling_number_id`=:calling_number_id ORDER BY `numbers`";
    $stmt = $db->prepare($sql);
    $calling_number_id =  preg_replace("/[^a-zA-Z0-9-]/","",$_GET['calling_number_id']);
    $stmt->bindValue(':calling_number_id', htmlspecialchars($calling_number_id));
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $isIncomingCaller=True;

  }

  if (strpos($_SERVER['REQUEST_URI'], "called_number_id") !== false) {

    $sql = "SELECT * FROM `called_numbers` WHERE `called_number_id`=:called_number_id ORDER BY `numbers`";
    $stmt = $db->prepare($sql);
    $called_number_id =  preg_replace("/[^a-zA-Z0-9-]/","",$_GET['called_number_id']);
    $stmt->bindValue(':called_number_id', htmlspecialchars($called_number_id));
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $isUserTelephone=True;

  }

  if (strpos($_SERVER['REQUEST_URI'], "call_id") !== false) {

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
    ON (inbound_calls.called_number_id = called_numbers.called_number_id)
    WHERE `call_id`=:call_id";
    $stmt = $db->prepare($sql);
    $call_id =  preg_replace("/[^a-zA-Z0-9-]/","",$_GET['call_id']);
    $stmt->bindValue(':call_id', htmlspecialchars($call_id));
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $isCall=True;

  }

  if (isset($_POST['modifyIncomingTelephoneNumber'])) {
    $calling_number_id = !empty($_POST['calling_number_id']) ? trim($_POST['calling_number_id']) : null;
    $calling_code = !empty($_POST['calling_code_in']) ? trim($_POST['calling_code_in']) : null;
    $prefix = !empty($_POST['prefix_in']) ? trim($_POST['prefix_in']) : null;
    $numbers = !empty($_POST['numbers_in']) ? trim($_POST['numbers_in']) : null;

    if (count($errors) == 0) {
      $sql = "UPDATE `calling_numbers` SET `calling_code`=:calling_code, `prefix`=:prefix, `numbers`=:numbers WHERE `calling_number_id`=:calling_number_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':calling_number_id', $calling_number_id);
      $stmt->bindValue(':calling_code', $calling_code);
      $stmt->bindValue(':prefix', $prefix);
      $stmt->bindValue(':numbers', $numbers);
      $result = $stmt->execute();
    }
    header('Location: calling_numbers.php');
  }

  if (isset($_POST['modifyUserTelephoneNumber'])) {
    $called_number_id = !empty($_POST['called_number_id']) ? trim($_POST['called_number_id']) : null;
    $calling_code = !empty($_POST['calling_code_in']) ? trim($_POST['calling_code_in']) : null;
    $prefix = !empty($_POST['prefix_in']) ? trim($_POST['prefix_in']) : null;
    $numbers = !empty($_POST['numbers_in']) ? trim($_POST['numbers_in']) : null;

    if (count($errors) == 0) {
      $sql = "UPDATE `called_numbers` SET `calling_code`=:calling_code, `prefix`=:prefix, `numbers`=:numbers WHERE `called_number_id`=:called_number_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':called_number_id', $called_number_id);
      $stmt->bindValue(':calling_code', $calling_code);
      $stmt->bindValue(':prefix', $prefix);
      $stmt->bindValue(':numbers', $numbers);
      $result = $stmt->execute();
    }
    header('Location: called_numbers.php');
  }

  if (isset($_POST['modifyIncomingCall'])) {
    $call_id = !empty($_POST['call_id']) ? trim($_POST['call_id']) : null;
    $calling_tele = !empty($_POST['calling_tele_in']) ? trim($_POST['calling_tele_in']) : null;
    $called_tele = !empty($_POST['called_tele_in']) ? trim($_POST['called_tele_in']) : null;
    $call_date = !empty($_POST['call_date_in']) ? trim($_POST['call_date_in']) : null;
    $call_state = !empty($_POST['call_state_in']) ? trim($_POST['call_state_in']) : null;
    $notes = !empty($_POST['notes_in']) ? trim($_POST['notes_in']) : null;

    if (count($errors) == 0) {
      $sql = "SELECT `calling_number_id` FROM `calling_numbers` WHERE `numbers` = :calling_tele";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':calling_tele', $calling_tele);
      $stmt->execute();
      $calling_number_id = $stmt->fetchColumn();

      $sql = "SELECT `called_number_id` FROM `called_numbers` WHERE `numbers` = :called_tele";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':called_tele', $called_tele);
      $stmt->execute();
      $called_number_id = $stmt->fetchColumn();

      $sql = "UPDATE `inbound_calls` SET `calling_number_id`=:calling_number_id, `called_number_id`=:called_number_id, `date_time`=:date_time, `state`=:state, `notes`=:notes WHERE `call_id`=:call_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':call_id', $call_id);
      $stmt->bindValue(':calling_number_id', $calling_number_id);
      $stmt->bindValue(':called_number_id', $called_number_id);
      $stmt->bindValue(':date_time', $call_date);
      $stmt->bindValue(':state', $call_state);
      $stmt->bindValue(':notes', $notes);
      $stmt->execute();
    }
    header('Location: index.php');
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" integrity="sha512-aEe/ZxePawj0+G2R+AaIxgrQuKT68I28qh+wgLrcAJOz3rxCP+TwrK5SPN+E5I+1IQjNtcfvb96HDagwrKRdBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha512-GDey37RZAxFkpFeJorEUwNoIbkTwsyC736KNSYucu1WJWFK9qTdzYub8ATxktr6Dwke7nbFaioypzbDOQykoRg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
  <div>

    <div>
      <?php if ($isIncomingCaller): ?>
        <div>

          <div>
            <form method="post" action="edit.php">

              <div hidden>
                <input readonly name="calling_number_id" type="text" value="<?php echo $result['calling_number_id']; ?>">
              </div>

              <div>
                calling_code:
                <select name="calling_code_in">
                  <?php $defaultCallingCode = $result['calling_code']; ?>
                  <option selected><?php echo $defaultCallingCode ?></option>
                  <?php
                  $sql = "SELECT `calling_code` FROM `country_calling_codes` ORDER BY `calling_code`";
                  $stmt = $db->prepare($sql);
                  $stmt->execute();

                  while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
                  ?>
                    <?php if($defaultCallingCode != $row_list['calling_code']){ ?>
                      <option value="<?php echo $row_list['calling_code']; ?>"><?php echo $row_list['calling_code']; ?></option>
                  <?php
                    }
                  }
                  ?>
                </select>
              </div>

              <div>
                prefix:
                <select name="prefix_in" >
                  <?php $defaultPrefix = $result['prefix']; ?>
                  <option selected><?php echo $result['prefix']; ?></option>
                  <?php
                    $sql = "SELECT `prefix` FROM `prefixes_hu` ORDER BY `prefix`";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();

                    while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
                    ?>
                      <?php if($defaultPrefix != $row_list['prefix']){ ?>
                        <option value="<?php echo $row_list['prefix']; ?>"><?php echo $row_list['prefix']; ?></option>
                    <?php
                      }
                    }
                    ?>
                </select>
              </div>

              <div>
                numbers:
                <input name="numbers_in" type="text" value="<?php echo $result['numbers']; ?>">
              </div>

              <div>
                <button type="submit" name="modifyIncomingTelephoneNumber">Módosítások mentése</button>
              </div>

            </form>
          </div>

          <div>
            <form action="calling_numbers.php">
              <input type="submit" value="Mégse" />
            </form>
          </div>

        </div>
      <?php endif; ?>
    </div>

    <div>
      <?php if ($isUserTelephone): ?>
        <div>

          <div>
            <form method="post" action="edit.php">

              <div hidden>
                <input readonly name="called_number_id" type="text" value="<?php echo $result['called_number_id']; ?>">
              </div>

              <div>
                calling_code:
                <select name="calling_code_in">
                  <?php $defaultCallingCode = $result['calling_code']; ?>
                  <option selected><?php echo $defaultCallingCode ?></option>
                  <?php
                  $sql = "SELECT `calling_code` FROM `country_calling_codes` ORDER BY `calling_code`";
                  $stmt = $db->prepare($sql);
                  $stmt->execute();

                  while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
                  ?>
                    <?php if($defaultCallingCode != $row_list['calling_code']){ ?>
                      <option value="<?php echo $row_list['calling_code']; ?>"><?php echo $row_list['calling_code']; ?></option>
                  <?php
                    }
                  }
                  ?>
                </select>
              </div>

              <div>
                prefix:
                <select name="prefix_in" >
                  <?php $defaultPrefix = $result['prefix']; ?>
                  <option selected><?php echo $result['prefix']; ?></option>
                  <?php
                    $sql = "SELECT `prefix` FROM `prefixes_hu` ORDER BY `prefix`";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();

                    while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
                    ?>
                      <?php if($defaultPrefix != $row_list['prefix']){ ?>
                        <option value="<?php echo $row_list['prefix']; ?>"><?php echo $row_list['prefix']; ?></option>
                    <?php
                      }
                    }
                    ?>
                </select>
              </div>

              <div>
                numbers:
                <input name="numbers_in" type="text" value="<?php echo $result['numbers']; ?>">
              </div>

              <div>
                <button type="submit" name="modifyUserTelephoneNumber">Módosítások mentése</button>
              </div>

            </form>
          </div>

          <div>
            <form action="calling_numbers.php">
              <input type="submit" value="Mégse" />
            </form>
          </div>

        </div>
      <?php endif; ?>
    </div>

    <div>
      <?php if ($isCall): ?>
        <div>
          <form method="post" action="edit.php">
            <div>

            <div hidden>
              <input readonly name="call_id" type="text" value="<?php echo $result['call_id']; ?>">
            </div>

            <div>
              Hívó telefonszám :
              <select name="calling_tele_in">
                <?php $callingTelenumber = $result['numbers1']; ?>
                <option selected><?php echo $result['numbers1']; ?></option>
                <?php
                $sql = "SELECT `numbers` FROM `calling_numbers`";
                $stmt = $db->prepare($sql);
                $stmt->execute();

                while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
                ?>
                <?php if($callingTelenumber != $row_list['numbers']){ ?>
                <option value="<?php echo $row_list['numbers']; ?>"><?php echo $row_list['numbers']; ?></option>
                <?php
                  }
                }
                ?>
              </select>
            </div>

            <div>
              Hívó telefonszám :
              <select name="called_tele_in">
                <?php $calledTelenumber = $result['numbers2']; ?>
                <option selected><?php echo $result['numbers2']; ?></option>
                <?php
                $sql = "SELECT `numbers` FROM `called_numbers`";
                $stmt = $db->prepare($sql);
                $stmt->execute();

                while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
                ?>
                <?php if($calledTelenumber != $row_list['numbers']){ ?>
                <option value="<?php echo $row_list['numbers']; ?>"><?php echo $row_list['numbers']; ?></option>
                <?php
                  }
                }
                ?>
              </select>
            </div>

            <div>
              Hívás dátuma:
              <div class='input-group date'>
                <?php $date_time = date("Y-m-d\TH:i:s", strtotime($result['date_time'])); ?>
                <input name="call_date_in" type='datetime-local' class="form-control" value="<?php echo $date_time; ?>"/>
              </div>
            </div>

            <div>
              Hívás állapota:
              <?php $defaultCallState = $result['state']; ?>
              <select name="call_state_in">
                <?php switch ($defaultCallState) {
                    case "missed": ?>
                        <option selected value="missed">Nem fogadva</option>
                        <option value="accepted">Fogadva</option>
                        <option value="denied">Elutasítva</option>
                        <?php break;
                    case "accepted": ?>
                        <option value="missed">Nem fogadva</option>
                        <option selected value="accepted">Fogadva</option>
                        <option value="denied">Elutasítva</option>
                        <?php break;
                    case "denied": ?>
                        <option value="missed">Nem fogadva</option>
                        <option value="accepted">Fogadva</option>
                        <option selected value="denied">Elutasítva</option>
                        <?php break;
                }
                ?>
              </select>
            </div>

            <div>
              Megjegyzés:
              <input name="notes_in" type="text" value="<?php echo $result['notes']; ?>">
            </div>

            <div>
              <button type="submit" name="modifyIncomingCall">Hívás mentése</button>
            </div>

          </form>

          </div>

          <div>
            <form action="index.php">
              <input type="submit" value="Mégse" />
            </form>
          </div>

        </div>
      <?php endif; ?>
    </div>

  </div>
</body>
</html>
