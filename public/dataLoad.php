<?php
require ("db_connect.php");

$username = $_GET["username"];

$sql = "SELECT data FROM users WHERE username = '$username'";

if ($result = mysqli_query($con, $sql)){
  // Fetch one and one row
  while ($row = mysqli_fetch_row($result)){
    //echo json_encode($row[0]);
    echo $row[0];
    //printf ("%s (%s)\n", $row[0], $row[1]);
  }
  mysqli_free_result($result);
}

mysqli_close($con);
?>