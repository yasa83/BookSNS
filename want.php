<?php
session_start();
require('dbconnect.php');

// 直接このページに来たらsignin.phpに飛ぶようにする
if(!isset($_SESSION['id'])){
    header('Location:signin.php');
    exit();
}

  if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

// ページネーション
const CONTENT_PER_PAGE = 9;

// -1などのページ数として不正な値を渡された場合の対策
$page = max($page, 1);

// ヒットしたレコードの数を取得するSQL
$sql_count = "SELECT COUNT(*) AS `cnt` FROM `wants`";

$stmt_count = $dbh->prepare($sql_count);
$stmt_count->execute();

$record_cnt = $stmt_count->fetch(PDO::FETCH_ASSOC);

// 取得したページ数を1ページあたりに表示する件数で割って何ページが最後になるか取得
$last_page = ceil($record_cnt['cnt'] / CONTENT_PER_PAGE);

// 最後のページより大きい値を渡された場合の対策
$page = min($page, $last_page);

$start = ($page - 1) * CONTENT_PER_PAGE;





//サインインユーザー情報取得
$sql = 'SELECT * FROM `users` WHERE `id` =?';
$data = array($_SESSION['id']);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

$title = '';
$price = '';
$detail = '';

if(!empty($_POST)){
    $title = $_POST['input_title'];
    $price = $_POST['input_price'];
    $detail = $_POST['input_detail'];

    if ($title == '') {
        $errors['title'] = 'blank';

    }

    if($price == ''){
        $errors['price'] = 'blank';
    }

    if($detail == '') {
        $errors['detail'] = 'blank';
        // var_dump($errors);
    }

$file_name = '';
    if(!isset($_GET['action'])){
        $file_name = $_FILES['img_name']['name'];
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

    if(empty($errors)){
        $date_str = date('YmdHis');
        $submit_file_name = $date_str . $file_name;

        move_uploaded_file($_FILES['img_name']['tmp_name'],'want_image/'.$submit_file_name);

        $sql = 'INSERT INTO `wants` SET `name` =?,`price`=?,`detail`=?,`img_name`=?';
        $data = array($title,$price,$detail,$submit_file_name);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        header('Location: want.php');
        exit();
    }

}

    $sql ='SELECT * FROM `wants` LIMIT '. CONTENT_PER_PAGE .' OFFSET ' . $start;
    $stmt= $dbh->prepare($sql);
    $stmt->execute();

    $feeds = array();

    while (true) {
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($record == false) {
            break;
        }

        $feeds[] = $record;
    }

?>

<!DOCTYPE html>
<html class="no-js"> 
<head>
    <meta charset="utf-8">
<!-- inital scale -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Present Box</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Free HTML5 Template by FREEHTML5.CO">
    <meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive">
    <meta name="author" content="FREEHTML5.CO">
    <link rel="icon" type="assets/images/favicon.png" href="assets/images/favicon.png">
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
    <link rel="stylesheet" href="assets/css/want.css">
    <link rel="stylesheet" media="(max-width: 640px)" href="assets/css/mobile.css">

  



    <!-- Modernizr JS -->
    <script src="assets/js/modernizr-2.6.2.min.js"></script>
</head>
<body>
    <!-- ナビゲーション始まり -->
<?php include('nav-var.php'); ?>
    <!-- ナビゲーション終わり -->

    <!-- ヘッダー -->
    <header id="fh5co-header" class="fh5co-cover fh5co-cover-sm" role="banner" style="background-image:url(assets/images/want.jpg);">
        <div class="overlay" style="padding: 20px"></div>


    <div class="container" style="padding-top:20px;">
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">Want List</h2>

        <form method="POST" action="want.php" enctype="multipart/form-data">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="input_title" class="form-control" id="title" placeholder="あなたが欲しいものを入力してください">
            <?php if(isset($errors['title']) && $errors['title'] =='blank'): ?>
            <p class="text-danger">Enter Title</p>
            <?php endif;?>
          </div>

          <div class="form-group">
            <label for="price">Price</label>
            <input type="text" name="input_price" class="form-control" value="" placeholder="だいたいの値段を入力してください">
            <?php if(isset($errors['price']) && $errors['price'] =='blank'): ?>
                <p class="text-danger">Enter Price</p>
            <?php endif;?>
          </div>

          <div class="form-group">
            <label for="detail">Detail</label>
            <input type="text" name="input_detail" class="form-control" rows="10" placeholder="どこで買えるかなどを入力してください" value="">
            <?php if(isset($errors['detail']) && $errors['detail'] =='blank'): ?>
                <p class="text-danger">Enter Detail</p>
            <?php endif;?>
          </div>

          <div class="form-group">
            <label for="img_name"></label>
            <input type="file" name="img_name" id="image/*"
            id="img_name">
            <?php if(isset($errors['img_name']) && $errors['img_name'] == 'blank'): ?>
            <p class="text-danger">画像を選択してください</p>
            <?php endif; ?>
            <?php if(isset($errors['img_name']) && $errors['img_name'] == 'type'): ?>
            <p class="text-danger">拡張子が「jpg」「png」「gif」「jpeg」の画像を選択して下さい</p>
            <?php endif; ?>
          </div>

          <br>

          <ul class="nav navbar-nav navbar-left">
            <li class="active"><a href="home.php" style="margin: 15px,background-color: black;">Back</a></li>
          </ul>
          <input type="submit" class="btn btn-primary" value="POST">
        </form>
      </div>
    </div>
        </div>
    </header>
    <!-- ヘッダー終わり -->


<!--wrap始まり-->

    <div id="fh5co-gallery">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center fh5co-heading animate-box">
                    <h2>your wants list</h2>
                    <p>あなたがほしいもの</p>
                </div>
            </div>
            <div class="row row-bottom-padded-md">
                <div class="col-md-12 ">
                    <ul id="fh5co-gallery-list">
                        <?php foreach ($feeds as $feed) {?>
                                <li class="one-third animate-box" data-animate-effect="fadeIn" style="background-image: url(want_image/<?php echo $feed['img_name']; ?>); "> <a data-target="want_<?php echo $feed['id']?>" class="modal-open" >
                                    <div class="case-studies-summary">
                                        <h2><?php echo $feed['name']; ?></h2>
                                        <h3><?php echo $feed['price']; ?></h3>
                                    </div>
                                    </a>
                                </li> 
                        <?php } ?>
                    </ul>
                </div>
                    <?php foreach ($feeds as $feed) {?>
                <div id="want_<?php echo $feed['id']?>" class="modal-content" style="width: 800px; height: 500px;">
                    <div class="row">
                        <div class="col-md-6">
                            <br><br><img src="want_image/<?php echo $feed['img_name']; ?>" class="picture-size">
                        </div>
                        <div class="col-md-6" style="font-size: 25px; line-height: 4em;">
                            <ul class="text-left" >
                                <li><?php echo $feed['name'] ?></li>
                                <li><?php echo $feed['price'] ?></li>
                                <li><?php echo $feed['detail'] ?></li>
                            </ul>
                            <br>
                            <p><a class="modal-close right-under">閉じる</a></p>
                        </div>
                    </div>
                </div>
                    <?php } ?>
                     <div aria-label="Page navigation">
                        <ul class="pager">
                            <?php if ($page == 1): ?>
                                <li class="previous disabled"><a><span aria-hidden="true">&larr;</span> Pre</a></li>
                            <?php else: ?>
                                <li class="previous"><a href="want.php?page=<?= $page - 1; ?>"><span aria-hidden="true">&larr;</span> Pre</a></li>
                            <?php endif; ?>
                            <?php if ($page == $last_page): ?>
                                <li class="next disabled"><a>Next <span aria-hidden="true">&rarr;</span></a></li>
                            <?php else: ?>
                                <li class="next"><a href="want.php?page=<?php echo $page + 1; ?>">Next <span aria-hidden="true">&rarr;</span></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


            <!--wrap終わり-->
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
<script src="assets/js/script.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/want.js"></script>
<script src="assets/js/script.js"></script>


</body>
</html>

