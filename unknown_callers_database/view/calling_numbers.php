<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'containers/head.php'; ?>
</head>

<body>
  <div class="container">
  <?php
    include './containers/navbar.php';
    include './../controller/calling_numbers_controller.php';
  ?>
  <?php if (!empty($_SESSION['is_logged_in'])): ?>
    <?php
      echo "<div class='table-container'><table class='table table-striped'>";

      echo "<tr><td> " . 'Hívott telefonszámok' . "</td><td>" . '' . "</td><td>" . '' . "</td></tr>";

      while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $full_telephone_number = $result['calling_code'] . " " . $result['prefix'] . " " . $result['numbers'];
        echo "<tr class='tele_rows' id='$result[calling_number_id]' name='$result[calling_number_id]'><td>" . $full_telephone_number . "</td><td><a href='edit.php?calling_number_id=$result[calling_number_id]'>Módosítás</a></td><td><a href='delete.php?calling_number_id=$result[calling_number_id]'>Törlés</a></td></tr>";
      }

      echo "</table></div>";
    ?>

    <form method="post" action="calling_numbers.php">

      <div>
        calling_code:
        <select name="calling_code_in">
          <option value="">--- Select ---</option>
          <?php
            while($calling_code_in_list=$calling_code_in->fetch(PDO::FETCH_ASSOC)){
          ?>
          <option value="<?php echo $calling_code_in_list['calling_code']; ?>"><?php echo $calling_code_in_list['calling_code']; ?></option>
          <?php
            }
          ?>
        </select>
      </div>

      <div>
        prefix:
        <select name="prefix_in" >
          <option value="">--- Select ---</option>
          <?php
            while($prefix_in_list=$prefix_in->fetch(PDO::FETCH_ASSOC)){
          ?>
          <option value="<?php echo $prefix_in_list['prefix']; ?>"><?php echo $prefix_in_list['prefix']; ?></option>
          <?php
            }
          ?>
        </select>
      </div>

      <div>
        numbers:
        <input name="numbers_in" type="text">
      </div>

      <div>
        <button type="submit" name="save_phone">Telefonszám mentése</button>
      </div>
    </form>
  <?php endif; ?>
  <?php
    include './containers/footer.php';
  ?>
  </div>
</body>
</html>
