<?php

require_once('dbconnect.php');

$book_id = $_GET['id'];


$sql = 'DELETE FROM `books` WHERE `id` = ?';
$data = array($book_id);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

header('Location:home.php');
exit();

?>