<?php 
require('dbconnect.php');
session_start();

$errors = [];

if(!empty($_POST)){
    $id = $_POST['id'];
    $password = $_POST['password'];

    if($id !='' && $password !=''){
        $sql = 'SELECT * FROM `users` WHERE `users_id`=?';
        $data = [$id];
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if($record == false){
        	$errors['signin'] = 'failed';
        }
        if(password_verify($password,$record['password'])){
        	$_SESSION['id'] = $record['id'];
        	header("Location: home.php");
        	exit();
        }else{
            $errors['signin'] = 'failed';
        }
    }else{
        $errors['signin'] = 'blank';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <title>Present Box</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="images/favicon.png" href="assets/images/favicon.png">
<link rel="stylesheet" type="text/css" href="assets/css/signin.css">
<link rel="stylesheet" media="(max-width: 640px)" href="assets/css/mobile.css">


</head>
<body background="assets/images/alcohl.jpg">
    <div class="limiter">
        <div class="container-login100" style="">
            <div class="wrap-login100 p-t-30 p-b-50">
                <span class="login100-form-title p-b-41">
                    Account Login
                </span>
                <form class="login100-form validate-form p-b-33 p-t-5" method="POST" action="signin.php">
                    <?php if(isset($errors['signin']) && $errors['signin'] == 'blank'): ?>
                        <p style="margin:30px 5px 5px 80px; color: red;">enter id and password correctly</p>
                    <?php endif; ?>
                    <?php if(isset($errors['signin']) && $errors['signin'] == 'failed'): ?>
                        <p style="margin:30px 5px 5px 80px; color: red;">failed Login</p>
                    <?php endif; ?>
                    <div class="wrap-input100 validate-input" data-validate = "Enter username">
                        <input class="input100" type="text" name="id" placeholder="User ID">
                        <span class="focus-input100" data-placeholder="&#xe82a;"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100" data-placeholder="&#xe80f;"></span>
                    </div>
                    <div class="container-login100-form-btn m-t-32">
                        <input type="submit" class="btn login100-form-btn" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>