<?php
  try {
    $db = new PDO('mysql:host=localhost;dbname=unknown_callers;charset=utf8mb4', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  } catch (PDOException $e) {
    echo "Kapcsolódás nem sikerült: ". $e->getMessage();
  }
?>
