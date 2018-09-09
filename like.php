<?php

require_once("dbconnect.php");

    $book_id = $_POST["book_id"];
    $user_id = $_POST["user_id"];

    // var_dump($user_id);
    // die();

    $sql = "INSERT INTO `likes` (`user_id`, `book_id`) VALUES (?, ?);";

    $data = [$user_id, $book_id];
    $stmt = $dbh->prepare($sql);
    $res = $stmt->execute($data);

     echo json_encode($res);
