<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'head.php'; ?>
</head>

<body>
  <div class="container">
    <?php
      include 'navbar.php';
      include 'edit_controller.php';
    ?>

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
