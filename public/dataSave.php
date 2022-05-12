<?php
require ("db_connect.php");

$growlist = mysqli_real_escape_string( $con, $_POST["growlist"] );
$username = $_POST["username"];

//$growlist = "{}";
//$username = "timbarden@outlook.com";

$sql = "UPDATE users SET data = '$growlist' WHERE username = '$username'";

if ($con->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

mysqli_close($con);


// consider switch to PDO
?>
