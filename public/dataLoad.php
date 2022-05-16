<?php
require ("db_connect.php");

$username = $_GET["username"];

$sql = "SELECT `userid`, `data` FROM `users` WHERE `username` = '$username'";
$objResults;

if ($result = mysqli_query($con, $sql)){
  while ($row = mysqli_fetch_assoc($result)){
    $objResults->userid = $row["userid"];
    $objResults->data = $row["data"];
    echo json_encode($objResults);
  }
  mysqli_free_result($result);
}

mysqli_close($con);
?>