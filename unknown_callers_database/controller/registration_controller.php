<?php

include("./../model/database_connection.php");
include("./../view/errors.php");

if(isset($_POST['reg_user'])) {

    $reg_username = !empty($_POST['reg_username']) ? trim($_POST['reg_username']) : null;
    $reg_password = !empty($_POST['reg_password']) ? trim($_POST['reg_password']) : null;
    $reg_permissions = 'user';

    if (empty($reg_username)) { array_push($errors, "reg_username hiba"); }
    if (empty($reg_password)) { array_push($errors, "reg_password hiba"); }

  if($reg_username != "" && $reg_password != "") {

    $sql = "SELECT * FROM `users` WHERE `name`=:reg_username";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':reg_username', $reg_username);
    $stmt->execute();
    $isUsernameTaken = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($isUsernameTaken) {
      array_push($errors, "Ez a felhasználónév már foglalt!");
    }

    if (count($errors) == 0) {

      $sql = "INSERT INTO `users` (`name`, `password`, `permissions`) VALUES (?, ?, ?)";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(1, $reg_username);
      $stmt->bindParam(2, $reg_password);
      $stmt->bindParam(3, $reg_permissions);
      $result = $stmt->execute();

      try {
        $query = "SELECT * FROM `users` WHERE `name`=:name AND `password`=:password";
        $stmt = $db->prepare($query);
        $stmt->bindParam('name', $reg_username, PDO::PARAM_STR);
        $stmt->bindValue('password', $reg_password, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->rowCount();
        $row   = $stmt->fetch(PDO::FETCH_ASSOC);
        if($count == 1 && !empty($row)) {
          $_SESSION['is_logged_in']   = "logged_in";
          $_SESSION['user_id']   = $row['user_id'];
          $_SESSION['name'] = $row['name'];
          $_SESSION['permission'] = $row['permission'];
          $msg1 = "Sikeres bejelentkezés!";
          header("location: user.php");
        } else {
          $msg1 = "Hibás név vagy jelszó!";
        }
      } catch (PDOException $e) {
        echo "Hiba: ".$e->getMessage();
      }

      header('Location: ./../view/user.php');
      exit;
    }
  }
}
?>
