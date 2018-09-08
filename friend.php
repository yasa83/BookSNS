<?php
session_start();
require('dbconnect.php');

//サインインユーザー情報取得
$sql = 'SELECT * FROM `users` WHERE `id` =?';
$data = array($_SESSION['id']);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);


// 友達情報登録
$name = '';
$birthday = '';
// $id = '';
$errors = array();

if(!empty($_POST)){
    $name = $_POST['friend_name'];
    $birthday = $_POST['friend_birthday'];
    // $friend_id = $_POST['friend_id'];

    if($name == ''){
        $errors['name'] = 'blank';
    }

    if($birthday == ''){
        $errors['birthday'] = 'blank';
    }

    // 画像名を取得
    $file_name = '';
    if(!isset($_GET['action'])){
        $file_name = $_FILES['friend_img_name']['name'];
    }
    if(!empty($file_name)){
        $file_type = substr($file_name, -4);
        $file_type = strtolower($file_type);
        if($file_type != '.jpg' && $file_type !='.png' && $file_type!='.gif' && $file_type!= 'jpeg'){
            $errors['img_name'] = 'type';
        }
    }else{
        $errors['img_name']= 'blank';
    }

    //エラーがなかった時の処理
    if(empty($errors)){
        $date_str = date('YmdHis');
        $submit_file_name = $date_str . $file_name;

        move_uploaded_file($_FILES['friend_img_name']['tmp_name'],'friend_profile_image/'.$submit_file_name);


        $sql = 'INSERT INTO `friends` SET `friends_name` =?, `birthday`=?,`friend_img` = ?, `created`= NOW(), `user_id`=?';
        $data = array($name,$birthday,$submit_file_name,$signin_user['id']);
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
     <link rel="icon" type="images/favicon.png" href="assets/images/favicon.png">
    <title>Present Box</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Free HTML5 Template by FREEHTML5.CO">
    <meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive">
    <meta name="author" content="FREEHTML5.CO">
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
       <link rel="stylesheet" media="(max-width: 640px)" href="assets/css/mobile.css">
    <!-- Modernizr JS -->
    <script src="assets/js/modernizr-2.6.2.min.js"></script>
</head>
<body>
    <?php include("nav-var.php"); ?>
    <!-- ヘッダー始まり -->
    <header id="fh5co-header" class="fh5co-cover fh5co-cover-sm" role="banner" style="background-image:url(assets/images/want.jpg);">
        <div class="overlay" style="padding: 5px"></div>
        <div class="container" style="padding-top:50px;">
            <div class="col-xs-10 col-xs-offset-1 thumbnail" style="background-color: #f5f5f5;">
                <h2 class="text-center content_header"><br>Add your friend</h2>
                <div class="col-xs-5 flexbox-container-vertical-center">
                    <img src="assets/images/girls.jpg" class="piture-ajust img-profile" style="width: 290px; height: 330px; border-radius: 5%;">
                </div>
                <div class="col-xs-7">
                    <form method="POST" action="friend.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Name</label>
                            <input type="text" name="friend_name" class="form-control"  placeholder="enter your friend's name">
                            <?php if(isset($errors['name']) && $errors['name'] == 'blank'): ?>
                                <p class="text-danger">Enter friend's name</p>
                            <?php endif;?>
                        </div>
                        <div class="form-group">
                            <label for="price">Birthdy</label>
                            <input type="date" name="friend_birthday" class="form-control"  placeholder="enter your friend's birthday">
                            <?php if(isset($errors['birthday']) && $errors['birthday'] == 'blank'): ?>
                            <p class="text-danger">Enter friend's birthday</p>
                            <?php endif;?>
                        </div>
                        <div class="form-group">
                            <div type="hidden" class="box" style="height: 40px;">
                           <!--  <label for="detail">search id</label>
                            <input type="text" name="friend_id" class="form-control" rows="10" placeholder="enter id,if your friend have id" value=""> -->
                        </div>
                        <div class="form-group">
                            <label for="img_name">picture</label>
                            <input type="file" name="friend_img_name" id="image/*"
                        id="img_name">
                            <?php if(isset($errors['img_name'])&& $errors['img_name'] == 'blank'): ?>
                                <p class="text-danger">enter friend's image</p>
                            <?php endif;?>
                            <?php if(isset($errors['img_name'])&& $errors['img_name'] == 'type'): ?>
                                <p class="text-danger">only 'jpg'.'png','gif' type</p>
                            <?php endif;?>
                        </div>
                    </div>
                    <br>
                <div class="text-center content_header">
                    <a href="home.php" style="margin: 15px,background-color: black;">Back</a>
                    <input type="submit" class="btn btn-primary" value="POST">
                </div>
                </form>
            </div>
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