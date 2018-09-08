<?php
session_start();
require('dbconnect.php');

// 直接このページに来たらsignin.phpに飛ぶようにする
if(!isset($_SESSION['email'])){
    header('Location:signin.php');
    exit();
}

//サインインユーザー情報取得
$sql = 'SELECT * FROM `users` WHERE `email` =?';
$data = array($_SESSION['email']);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

// エラーの初期化
$errors = array();

//空の配列を用意
$user_name = '';
$email = '';

// 編集ボタンを押したときの処理
if(!empty($_POST)){
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];

    if($user_name == '' ){
        $errors['user_name'] = 'blank';
    } 

    if($email == ''){
        $errors['email'] = 'blank';
    }

    //エラーがなかった時の処理
    if(empty($errors)){
    $update_sql = "UPDATE `users` SET `user_name` = ?,`email` = ? WHERE `id` = ? ";
    $data = array($user_name,$email,$signin_user['id']);
    $stmt = $dbh->prepare($update_sql);
    $stmt->execute($data);

    header('Location:signin.php');
    exit();
    }
}


?>

<!DOCTYPE html>
<html class="no-js"> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BookSNS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Free HTML5 Template by FREEHTML5.CO">
    <meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive">
    <meta name="author" content="FREEHTML5.CO">
    <link rel="icon" type="images/favicon.png" href="assets/images/favicon.png">
    <link href='https://fonts.googleapis.com/css?family=Work+Sans:400,300,600,400italic,700' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Sacramento" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!-- Icomoon Icon Fonts-->
    <link rel="stylesheet" href="assets/css/icomoon.css">
    <!-- Bootstrap  -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <!-- Theme style  -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Modernizr JS -->
    <script src="assets/js/modernizr-2.6.2.min.js"></script>
</head>
<body>
    <?php include('nav-var.php'); ?>
    <!-- ヘッダー始まり -->
    <header id="fh5co-header" class="fh5co-cover fh5co-cover-sm" role="banner" style="background-image:url(assets/images/bookshelf.jpg);">
        <div class="overlay"></div>
        <div class="container" style="padding-top:45px;">
            <div class="col-xs-8 col-xs-offset-2 thumbnail profile">
                <h2 class="text-center post">Your Profile</h2>
                <img  class="profile_picture thumbnail" src="user_profile_image/<?php echo $signin_user['img_name']?>">
                <form method="POST" action="profile.php" enctype="multipart/form-data">
                    <div class="form-group users">
                        <label for="user_name">Your user name</label>
                        <textarea name="user_name" rows="2" cols="20"><?php echo $signin_user['user_name']?></textarea>
                        <?php if(isset($errors['user_name']) && $errors['user_name'] == 'blank'): ?>
                            <p class="text-danger">Enter user name</p>
                        <?php endif;?>
                    </div>
                    <div class="form-group users">
                        <label for="detail">Your Email</label>
                        <textarea rows="2" cols="20" name="email"><?php echo $signin_user['email']?></textarea>
                        <?php if(isset($errors['email'])&& $errors['email'] == 'blank'): ?>
                            <p class="text-danger">Enter your email</p>
                        <?php endif;?>
                    </div>
                    <ul class="nav navbar-nav users">
                        <li class="active"><a href="home.php" style="margin: 15px,background-color: black;">back to list</a></li>
                        <input type="submit" class="btn btn-primary" value="Edit">
                    </ul>
                </form>
            </div>
        </div>
    </header>
    <!-- ヘッダー終わり -->

    <?php include('footer.php'); ?>
    <!-- jQuery -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- jQuery Easing -->
    <script src="assets/js/jquery.easing.1.3.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Waypoints -->
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <!-- Carousel -->
    <script src="assets/js/owl.carousel.min.js"></script>
    <!-- countTo -->
    <script src="assets/js/jquery.countTo.js"></script>
    <!-- Stellar -->
    <script src="assets/js/jquery.stellar.min.js"></script>
    <!-- Magnific Popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/magnific-popup-options.js"></script>
    <!-- Main -->
    <script src="assets/js/main.js"></script>
</body>
</html>

