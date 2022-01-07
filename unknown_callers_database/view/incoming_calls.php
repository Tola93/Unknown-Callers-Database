<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'containers/head.php'; ?>
</head>

<body>
  <div class="container">
  <?php
    include './containers/navbar.php';
    include './../controller/incoming_calls_controller.php';
  ?>
  <?php if (!empty($_SESSION['is_logged_in'])): ?>
    <div>
      <?php
        echo "<div class='table-container'><table class='table table-striped'>";

        echo "<tr><td>" . 'Hívó telefonszám' . "</td><td>" . 'Hívás időpontja' . "</td><td>" . 'Hívott telefonszám' . "</td><td>" . 'Hívás állapota' . "</td><td>" . 'Megjegyzés' . "</td><td>" . '' . "</td><td>" . '' . "</td></tr>";
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $calling_full_telephone_number = $result['calling_code1'] . " " . $result['prefix1'] . " " . $result['numbers1'];
          $called_full_telephone_number = $result['calling_code2'] . " " . $result['prefix2'] . " " . $result['numbers2'];
          echo "<tr><td>" . $calling_full_telephone_number . "</td><td>" . $result['date_time'] . "</td><td>" .   $called_full_telephone_number . "</td><td>" . $result['state'] . "</td><td>" . $result['notes'] . "</td><td><a href='edit.php?call_id=$result[call_id]'>Módosítás</a></td><td><a href='delete.php?call_id=$result[call_id]'>Törlés</a></td></tr>";
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
            <option
              value="<?php echo $calling_tele_in_list['calling_number_id']; ?>">
              <?php echo $calling_tele_in_list['calling_code'] . " " . $calling_tele_in_list['prefix'] . " " . $calling_tele_in_list['numbers']; ?>
            </option>
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
                  <option
                    value="<?php echo $called_tele_in_list['called_number_id']; ?>">
                    <?php echo $called_tele_in_list['calling_code'] . " " . $called_tele_in_list['prefix'] . " " . $called_tele_in_list['numbers']; ?>
                  </option>
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
  <?php endif; ?>
</body>
</html>
