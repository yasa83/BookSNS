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
$title = '';
$reason = '';

// 登録ボタンを押したときの処理
if(!empty($_POST)){
    $title = $_POST['input_title'];
    $reason = $_POST['input_reason'];
    $user_id = $signin_user['id'];


    if($title == '' ){
        $errors['title'] = 'blank';
    } 

    if($reason == ''){
        $errors['reason'] = 'blank';
    }

    // 画像名を取得
    $file_name = '';
    if(!isset($_GET['action'])){
        $file_name = $_FILES['input_img_name']['name'];
    }
    if(!empty($file_name)){
        $file_type = substr($file_name, -4);
        $file_type = strtolower($file_type);
        if($file_type != '.jpg' && $file_type !='.png' && $file_type!='.gif' && $file_type!='jpeg'){
            $errors['img_name'] = 'type';
        }
    }else{
        $errors['img_name']= 'blank';
    }

    //エラーがなかった時の処理
    if(empty($errors)){
        $date_str = date('YmdHis');
        $submit_file_name = $date_str . $file_name;

        move_uploaded_file($_FILES['input_img_name']['tmp_name'],'book_image/'.$submit_file_name);


        $sql = 'INSERT INTO `books` SET `title` =?, `user_id`=?, `reason` = ?,`img_name` = ?, `created` = NOW()';
        $data = array($title,$user_id,$reason,$submit_file_name);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        header('Location: home.php');
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
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center post">Post recommended book</h2>
                <form method="POST" action="post.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="book">Book title</label>
                        <input type="text" name="input_title" class="form-control" placeholder="Enter book title">
                        <?php if(isset($errors['title']) && $errors['title'] == 'blank'): ?>
                            <p class="text-danger">Enter book title</p>
                        <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="detail">Detail</label>
                        <textarea rows="5" cols="80" name="input_reason" placeholder="Enter recommended reason"></textarea>
                        <?php if(isset($errors['reason'])&& $errors['reason'] == 'blank'): ?>
                            <p class="text-danger">Enter recommended reason</p>
                        <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="img_name"></label>
                        <input type="file" name="input_img_name" id="image/*"
                        id="img_name">
                        <?php if(isset($errors['img_name'])&& $errors['img_name'] == 'blank'): ?>
                            <p class="text-danger">Enter book's image</p>
                        <?php endif;?>
                        <?php if(isset($errors['img_name'])&& $errors['img_name'] == 'type'): ?>
                            <p class="text-danger">only 'jpg'.'png','gif' type</p>
                        <?php endif;?>
                    </div>
                    <br>
                    <ul class="nav navbar-nav navbar-left">
                        <li class="active"><a href="home.php" style="margin: 15px,background-color: black;">back to list</a></li>
                    </ul>
                    <input type="submit" class="btn btn-primary" value="Post">
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

