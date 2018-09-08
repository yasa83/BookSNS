<?php

require_once('dbconnect.php');

$present_id = $_GET['id'];
$friend_id = $_GET['friend'];

$sql = 'DELETE FROM `presents` WHERE `id` = ?';
$data = array($present_id);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

header('Location: list.php?id=' . $friend_id);
exit();

?>