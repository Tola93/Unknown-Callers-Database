<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'head.php'; ?>
</head>

<body>
  <div class="container">
    <?php
      include 'navbar.php';
      include 'delete_controller.php';
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
                incoming_calls.date_time:
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
