<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'containers/head.php'; ?>
</head>

<body>
  <div class="container">
    <?php
      include './containers/navbar.php';
      include './../controller/delete_controller.php';

      if (!empty($_SESSION['is_logged_in'])) {
        $user_id = $_SESSION['user_id'];
      } else {
        $user_id = 0;
      }
    ?>

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
                Hívó telefonszám:
                <?php $callingTelenumber = $result['calling_code'] . " " . $result['prefix'] . " " . $result['numbers']; ?>
                <input readonly name="calling_tele_in" type="text" value="<?php echo $callingTelenumber; ?>">
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
                Hívott telefonszám:
                <?php $calledTelenumber = $result['calling_code'] . " " . $result['prefix'] . " " . $result['numbers']; ?>
                <input readonly name="called_tele_in" type="text" value="<?php echo $calledTelenumber; ?>">
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
                Hívó telefonszám:
                <?php $callingTelenumber = $result['calling_code1'] . " " . $result['prefix1'] . " " . $result['numbers1']; ?>
                <input readonly name="calling_tele_in" type="text" value="<?php echo $callingTelenumber; ?>">
              </div>

              <div>
                Hívás dátuma:
                <input readonly name="date_time" type="text" value="<?php echo $result['date_time']; ?>">
              </div>

              <div>
                Hívott telefonszám:
                <?php $calledTelenumber = $result['calling_code2'] . " " . $result['prefix2'] . " " . $result['numbers2']; ?>
                <input readonly name="called_tele_in" type="text" value="<?php echo $calledTelenumber; ?>">
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
    <?php
      include './containers/footer.php';
    ?>
  </div>
</body>
</html>
