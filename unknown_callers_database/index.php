<?php
  include("database_connection.php");
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
    <form action="calling_numbers.php">
      <input type="submit" value="Hívó számok" />
    </form>
    <form action="called_numbers.php">
      <input type="submit" value="Hívott számok" />
    </form>
  </div>
  <div>
    <?php
      $sql =
      "SELECT `calling_numbers.calling_code`, `calling_numbers.prefix`, `calling_numbers.numbers`, `inbound_calls.date_time`, `called_numbers.calling_code`, `called_numbers.prefix`, `called_numbers.numbers` 
      FROM `inbound_calls`
      JOIN `calling_numbers` ON `inbound_calls.calling_number_id` = `calling_numbers.calling_number_id`
      JOIN `called_numbers` ON `inbound_calls.called_number_id` = `called_numbers.called_number_id`
      ORDER BY `calling_numbers.numbers`, `inbound_calls.date_time`";
      //$sql = "SELECT `calling_numbers.calling_code`, `calling_numbers.prefix`, `calling_numbers.numbers`, `inbound_calls.date_time`, `called_numbers.calling_code`, `called_numbers.prefix`, `called_numbers.numbers` FROM `inbound_calls` JOIN `calling_numbers` ON `inbound_calls.calling_number_id` = `calling_numbers.calling_number_id` JOIN `called_numbers` ON `inbound_calls.called_number_id` = `called_numbers.called_number_id` ORDER BY `calling_numbers.numbers`, `inbound_calls.date_time`";
      //$sql = "SELECT * FROM `inbound_calls` ORDER BY `date_time`";
      $stmt = $db->prepare($sql);
      $stmt->execute();

      echo "<table class='table table-striped'>";
      echo "<tr><td>" . 'calling_numbers.calling_code' . "</td><td> " . 'calling_numbers.prefix' . "</td><td>" . 'calling_numbers.numbers' . "</td><td>" . 'inbound_calls.date_time' . "</td><td>" . 'called_numbers.calling_code' . "</td><td>" . 'called_numbers.prefix' . "</td><td>" . 'called_numbers.numbers' . "</td></tr>";
      while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>" . $result['calling_numbers.calling_code'] . "</td><td> " . $result['calling_numbers.prefix'] . "</td><td>" . $result['calling_numbers.numbers'] . "</td><td>" . $result['inbound_calls.date_time'] . "</td><td>" . $result['called_numbers.calling_code'] . "</td><td>" . $result['called_numbers.prefix'] . "</td><td>" . $result['called_numbers.numbers'] . "</td></tr>";
        //echo "<tr><td>" . $result['calling_numbers.calling_code'] . "</td><td> " . $result['calling_numbers.prefix'] . "</td><td>" . $result['calling_numbers.numbers'] . "</td><td>" . $result['inbound_calls.date_time'] . "</td><td>" . $result['called_numbers.calling_code'] . "</td><td>" . $result['called_numbers.prefix'] . "</td><td>" . $result['called_numbers.numbers'] . "</td></tr>";
      }
      /*
      echo "<tr><td>" . 'call_id' . "</td><td> " . 'calling_number_id' . "</td><td>" . 'called_number_id' . "</td><td>" . 'date_time' . "</td><td>" . 'state' . "</td><td>" . 'notes' . "</td></tr>";
      while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>" . $result['call_id'] . "</td><td> " . $result['calling_number_id'] . "</td><td>" . $result['called_number_id'] . "</td><td>" . $result['date_time'] . "</td><td>" . $result['state'] . "</td><td>" . $result['notes'] . "</td></tr>";
      }
      */
      echo "</table>";
    ?>
  </div>
  <div>
    <form id="form1" name="form1" method="post" action="<?php echo $PHP_SELF; ?>">
        Hívó telefonszám :
        <select Emp Name='NEW'>
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
    </form>
    <form id="form2" name="form2" method="post" action="<?php echo $PHP_SELF; ?>">
        Hívott telefonszám :
        <select Emp Name='NEW'>
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
    </form>
    <form id="form3" name="form3" method="post" action="<?php echo $PHP_SELF; ?>">
    <div class="container">
       <div class="row">
          <div class='col-sm-6'>
             <div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                   <input type='text' class="form-control"/>
                   <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                   </span>
                </div>
             </div>
          </div>
          <script type="text/javascript">
             $(function () {
                 $('#datetimepicker2').datetimepicker({
                     locale: 'hu'
                 });
             });
          </script>
       </div>
    </div>
    </form>
    <div>
      <label>Megjegyzés: </label><br>
      <input id="userIDField" type="text" name="userID"><br><br>
    </div>
  </div>
</body>
</html>
