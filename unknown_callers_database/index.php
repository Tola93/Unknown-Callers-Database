<?php
  include("database_connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<?php
        # Fetch records
        $sql = "SELECT * FROM `inbound_calls` ORDER BY `date_time`";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        echo "<table class='table table-striped'>";
        echo "<tr><td>" . 'call_id' . "</td><td> " . 'calling_number_id' . "</td><td>" . 'called_number_id' . "</td><td>" . 'date_time' . "</td><td>" . 'state' . "</td><td>" . 'notes' . "</td></tr>";
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr><td>" . $result['call_id'] . "</td><td> " . $result['calling_number_id'] . "</td><td>" . $result['called_number_id'] . "</td><td>" . $result['date_time'] . "</td><td>" . $result['state'] . "</td><td>" . $result['notes'] . "</td></tr>";
        }
        echo "</table>";
?>

</body>
</html>
