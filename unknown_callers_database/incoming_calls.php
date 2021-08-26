<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'head.php'; ?>
</head>

<body>
  <div class="container">
    <?php
      include 'navbar.php';
      include 'incoming_calls_controller.php';
    ?>
    <div>
      <?php
        echo "<div class='table-container'><table class='table table-striped'>";

        echo "<tr><td>" . 'calling_numbers.calling_code' . "</td><td> " . 'calling_numbers.prefix' . "</td><td>" . 'calling_numbers.numbers' . "</td><td>" . 'incoming_calls.date_time' . "</td><td>" . 'called_numbers.calling_code' . "</td><td>" . 'called_numbers.prefix' . "</td><td>" . 'called_numbers.numbers' . "</td><td>" . 'state' . "</td><td>" . 'notes' . "</td></tr>";
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr><td>" . $result['calling_code1'] . "</td><td> " . $result['prefix1'] . "</td><td>" . $result['numbers1'] . "</td><td>" . $result['date_time'] . "</td><td>" . $result['calling_code2'] . "</td><td>" . $result['prefix2'] . "</td><td>" . $result['numbers2'] . "</td><td>" . $result['state'] . "</td><td>" . $result['notes'] . "</td><td><a href='edit.php?call_id=$result[call_id]'>Módosítás</a></td><td><a href='delete.php?call_id=$result[call_id]'>Törlés</a></td></tr>";
        }

        echo "</table></div>";
      ?>
    </div>
    <div>
      <form method="post" action="incoming_calls.php">
        <div>
          Hívó telefonszám :
          <select name="calling_tele_in">
            <option value="">--- Select ---</option>
            <?php
              while($calling_tele_in_list=$calling_tele_in->fetch(PDO::FETCH_ASSOC)){
            ?>
                <option value="<?php echo $calling_tele_in_list['numbers']; ?>"><?php echo $calling_tele_in_list['numbers']; ?></option>
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
                while($called_tele_in_list=$called_tele_in->fetch(PDO::FETCH_ASSOC)){
              ?>
                  <option value="<?php echo $called_tele_in_list['numbers']; ?>"><?php echo $called_tele_in_list['numbers']; ?></option>
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
