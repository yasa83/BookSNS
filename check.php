<?php
session_start();
require('dbconnect.php');

if(!isset($_SESSION['register'])){
    header('Location:signup.php');
    exit();
}

    $name = $_SESSION['register']['name'];
    $email = $_SESSION['register']['email'];
    $password = $_SESSION['register']['password'];
    $img_name = $_SESSION['register']['img_name'] ;

    if(!empty($_POST)){
        $sql = 'INSERT INTO `users` SET `user_name` =?, `email`=?, `password`=?, `img_name`=?';
        $data = array($name,$email,password_hash($password,PASSWORD_DEFAULT),$img_name);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        unset($_SESSION['register']);
        header('Location: thanks.php');
        exit();
    }


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookSNS</title>
    <link rel="icon" type="images/favicon.png" href="assets/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/check.css">
    <link rel="stylesheet" media="(max-width: 640px)" href="assets/css/mobile.css">
</head>
<body background="assets/images/bookshelf.jpg">
    <div class="container">
        <div class="row">
            <!-- ここから -->
            <div class="col-xs-6 thumbnail">
                <h2 class="text-center content_header">Can you check again?</h2>
                <div class="row">
                    <div class="col-xs-4">
                        <img src="user_profile_image/<?php echo htmlspecialchars($img_name); ?>" width="300" class="img-responsive img-thumbnail">
                    </div>
                    <div class="col-xs-8">
                        <div>
                            <span>user name</span>
                            <p class="lead"><?php echo htmlspecialchars($name); ?></p>
                        </div>
                        <div>
                            <span>email</span>
                            <p class="lead"><?php echo htmlspecialchars($email); ?></p>
                        </div>
                        <div>
                            <span>password</span>
                            <p class="lead">●●●●●●●●</p>
                        </div>
                        <form method="POST" action="check.php">
                            <input type="hidden" name="action" value="submit">
                            <input type="submit" class="btn btn-primary" value="user register">
                            <a href="signup.php?action=rewrite" class="btn btn-default" font color="#F14E95">&laquo;&nbsp;back</a>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
