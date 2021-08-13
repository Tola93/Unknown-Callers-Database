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

  if (isset($_POST['removeIncomingTelephoneNumber'])) {
    $calling_number_id = !empty($_POST['calling_number_id']) ? trim($_POST['calling_number_id']) : null;
    $calling_code = !empty($_POST['calling_code_in']) ? trim($_POST['calling_code_in']) : null;
    $prefix = !empty($_POST['prefix_in']) ? trim($_POST['prefix_in']) : null;
    $numbers = !empty($_POST['numbers_in']) ? trim($_POST['numbers_in']) : null;

    if (count($errors) == 0) {
      $sql = "DELETE FROM `calling_numbers` WHERE `calling_number_id`=:calling_number_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':calling_number_id', $calling_number_id);
      $result = $stmt->execute();
    }
        header('Location: calling_numbers.php');
  }

  if (isset($_POST['removeUserTelephoneNumber'])) {
    $called_number_id = !empty($_POST['called_number_id']) ? trim($_POST['called_number_id']) : null;
    $calling_code = !empty($_POST['calling_code_in']) ? trim($_POST['calling_code_in']) : null;
    $prefix = !empty($_POST['prefix_in']) ? trim($_POST['prefix_in']) : null;
    $numbers = !empty($_POST['numbers_in']) ? trim($_POST['numbers_in']) : null;

    if (count($errors) == 0) {
      $sql = "DELETE FROM `called_numbers` WHERE `called_number_id`=:called_number_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':called_number_id', $called_number_id);
      $result = $stmt->execute();
    }
    header('Location: called_numbers.php');
  }

  if (isset($_POST['removeIncomingCall'])) {
    $call_id = !empty($_POST['call_id']) ? trim($_POST['call_id']) : null;

    if (count($errors) == 0) {
      $sql = "DELETE FROM `inbound_calls` WHERE `call_id`=:call_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':call_id', $call_id);
      $result = $stmt->execute();
    }
    header('Location: incoming_calls.php');
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
            <form method="post" action="delete.php">
              Biztos, hogy törölni szeretnéd ezt a telefonszámot?

              <div hidden>
                <input readonly name="calling_number_id" type="text" value="<?php echo $result['calling_number_id']; ?>">
              </div>

              <div>
                calling_code:
                <input readonly name="calling_code_in" type="text" value="<?php echo $result['calling_code']; ?>">
              </div>

              <div>
                prefix:
                <input readonly name="prefix_in" type="text" value="<?php echo $result['prefix']; ?>">
              </div>

              <div>
                numbers:
                <input readonly name="numbers_in" type="text" value="<?php echo $result['numbers']; ?>">
              </div>

              <div>
                <button type="submit" name="removeIncomingTelephoneNumber">Telefonszám törlése</button>
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
            <form method="post" action="delete.php">
              Biztos, hogy törölni szeretnéd ezt a telefonszámot?

              <div hidden>
                <input readonly name="called_number_id" type="text" value="<?php echo $result['called_number_id']; ?>">
              </div>

              <div>
                calling_code:
                <input readonly name="calling_code_in" type="text" value="<?php echo $result['calling_code']; ?>">
              </div>

              <div>
                prefix:
                <input readonly name="prefix_in" type="text" value="<?php echo $result['prefix']; ?>">
              </div>

              <div>
                numbers:
                <input readonly name="numbers_in" type="text" value="<?php echo $result['numbers']; ?>">
              </div>

              <div>
                <button type="submit" name="removeUserTelephoneNumber">Telefonszám törlése</button>
              </div>

            </form>
          </div>

          <div>
            <form action="called_numbers.php">
              <input type="submit" value="Mégse" />
            </form>
          </div>

        </div>
      <?php endif; ?>
    </div>

    <div>
      <?php if ($isCall): ?>
        <div>

          <div>
            <form method="post" action="delete.php">
              Biztos, hogy törölni szeretnéd ezt a hívást?

              <div hidden>
                <input readonly name="call_id" type="text" value="<?php echo $result['call_id']; ?>">
              </div>

              <div>
                calling_numbers.calling_code:
                <input readonly name="calling_code1" type="text" value="<?php echo $result['calling_code1']; ?>">
              </div>

              <div>
                calling_numbers.prefix:
                <input readonly name="prefix1" type="text" value="<?php echo $result['prefix1']; ?>">
              </div>

              <div>
                calling_numbers.numbers:
                <input readonly name="numbers1" type="text" value="<?php echo $result['numbers1']; ?>">
              </div>

              <div>
                inbound_calls.date_time:
                <input readonly name="date_time" type="text" value="<?php echo $result['date_time']; ?>">
              </div>

              <div>
                called_numbers.calling_code:
                <input readonly name="calling_code2" type="text" value="<?php echo $result['calling_code2']; ?>">
              </div>

              <div>
                called_numbers.prefix	:
                <input readonly name="prefix2" type="text" value="<?php echo $result['prefix2']; ?>">
              </div>

              <div>
                called_numbers.numbers:
                <input readonly name="numbers2" type="text" value="<?php echo $result['numbers2']; ?>">
              </div>

              <div>
                state:
                <input readonly name="state" type="text" value="<?php echo $result['state']; ?>">
              </div>

              <div>
                notes:
                <input readonly name="notes" type="text" value="<?php echo $result['notes']; ?>">
              </div>

              <div>
                <button type="submit" name="removeIncomingCall">Hívás törlése</button>
              </div>
            </form>
        </div>

        <div>
          <form action="incoming_calls.php">
            <input type="submit" value="Mégse" />
          </form>
        </div>

        </div>
      <?php endif; ?>
    </div>
    
  </div>
</body>
</html>
