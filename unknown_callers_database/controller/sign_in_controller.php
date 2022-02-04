<?php

include("./../model/database_connection.php");
include("./../view/errors.php");

$msg1 = "";
	if(isset($_POST['submitBtnLogin'])) {

		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

	if($username != "" && $password != "") {
		try {
			$query = "SELECT * FROM `users` WHERE `name`=:name AND `password`=:password";
			$stmt = $db->prepare($query);
			$stmt->bindParam('name', $username, PDO::PARAM_STR);
			$stmt->bindValue('password', $password, PDO::PARAM_STR);
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
	} else {
		$msg1 = "Név és jelszó kell!";
	}
	}
?>
