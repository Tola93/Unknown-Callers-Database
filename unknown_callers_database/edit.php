<?php
  include("database_connection.php");

  $errors = array();

  $sql = "SELECT * FROM `calling_numbers` WHERE `calling_number_id`=:calling_number_id ORDER BY `numbers`";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':calling_number_id', $_GET['calling_number_id']);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (isset($_POST['sv_updt'])) {

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
      <form action="index.php">
        <input type="submit" value="Index"/>
      </form>
      <form action="calling_numbers.php">
        <input type="submit" value="Hívó számok" />
      </form>
      <form action="called_numbers.php">
        <input type="submit" value="Hívott számok" />
      </form>
    </div>
    <div>
      <form method="post" action="edit.php">
        <div>
          calling_number_id:
          <input readonly name="calling_number_id" type="text" value="<?php echo $result['calling_number_id']; ?>">
        </div>
        <div>
          calling_code:
          <select name="calling_code_in">
            <option selected><?php echo $result['calling_code']; ?></option>
            <?php
              $sql = "SELECT `calling_code` FROM `country_calling_codes` ORDER BY `calling_code`";
              $stmt = $db->prepare($sql);
              $stmt->execute();
            ?>
            <?php
              while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
            ?>
            <option value="<?php echo $row_list['calling_code']; ?>">
              <?php echo $row_list['calling_code']; ?>
            </option>
            <?php
              }
            ?>
          </select>
        </div>
        <div>
          prefix:
          <select name="prefix_in" >
            <option selected><?php echo $result['prefix']; ?></option>
            <?php
              $sql = "SELECT `prefix` FROM `prefixes_hu` ORDER BY `prefix`";
              $stmt = $db->prepare($sql);
              $stmt->execute();
            ?>
            <?php
              while($row_list=$stmt->fetch(PDO::FETCH_ASSOC)){
            ?>
            <option value="<?php echo $row_list['prefix']; ?>">
              <?php echo $row_list['prefix']; ?>
            </option>
            <?php
              }
            ?>
          </select>
        </div>
        <div>
          numbers:
          <input name="numbers_in" type="text" value="<?php echo $result['numbers']; ?>">
        </div>
        <div>
          <button type="submit" name="sv_updt">Módosítások mentése</button>
        </div>
      </form>
    </div>
    <div>
      <form action="calling_numbers.php">
        <input type="submit" value="Mégse" />
      </form>
    </div>
  </div>
</body>
</html>
