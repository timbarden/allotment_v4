<?php
require ("db_connect.php");

$growlist = $_POST["growlist"];
//$username = $_POST["username"];

//$growlist = "{}";
$username = "timbarden@outlook.com";

echo $growlist."<br>";
echo $username;

$sql = "UPDATE users SET data = '$growlist' WHERE username = '$username'";

if ($con->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

mysqli_close($con);


/* $growlist = $_POST["growlist"];
$username = $_POST["username"];


$host = 'localhost';
$db   = 'wpwxqzcc_plotbook';
$user = 'wpwxqzcc_plotbookuser';
$pass = 'abtG5dazrzySgS8';
$port = "3306";
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $query = "UPDATE users SET data = $growlist WHERE username = 'timbarden@outlook.com'";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array($data, $username));
    echo "yep";
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
    echo "nope";
} */
?>