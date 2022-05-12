<?php
$db = $_POST["db"];
$dbUser = $_POST["dbUser"];
$dbPass = $_POST["dbPass"];

require ("db_connect.php");

$userid = $_POST["userid"];
$username = $_POST["username"];
$growlist = mysqli_real_escape_string( $con, $_POST["growlist"] );

$sql = "UPDATE users SET data = '$growlist' WHERE username = '$username'";
// if (is_null($userid)){
//     $sql = "INSERT INTO `users` (`username`, `data`) VALUES ('$username', '$growlist')"
// } else {
//     $sql = "INSERT INTO `users` (`userid`, `username`, `data`) VALUES ('$userid', '$username', '$growlist') ON DUPLICATE KEY UPDATE `data` = VALUES(`data`)"
// }

if ($con->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

mysqli_close($con);

// consider switch to PDO?
?>