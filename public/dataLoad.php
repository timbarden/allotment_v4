<?php
$db = $_POST["db"];
$dbUser = $_POST["dbUser"];
$dbPass = $_POST["dbPass"];

require ("db_connect.php");

$username = $_GET["username"];

//$sql = "SELECT data FROM users WHERE username = '$username'";
$sql = "SELECT `userid`, `data` FROM `users` WHERE `username` = '$username'";
$objResults;

if ($result = mysqli_query($con, $sql)){
  // Fetch one and one row
  while ($row = mysqli_fetch_assoc($result)){
    //echo json_encode($row[0]);
    $objResults->userid = $row["userid"];
    $objResults->data = $row["data"];
    echo json_encode($objResults);
    //echo $objResults;
    //printf("%s (%s)\n", $row["userid"], $row["data"]);
  }
  mysqli_free_result($result);
}

mysqli_close($con);
?>