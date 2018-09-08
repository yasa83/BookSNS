<?php
session_start();
require('dbconnect.php');

// 直接このページに来たらsignin.phpに飛ぶようにする
if(!isset($_SESSION['id'])){
    header('Location:signin.php');
    exit();
}

//サインインユーザー情報取得
$sql = 'SELECT * FROM `users` WHERE `id` =?';
$data = array($_SESSION['id']);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

// エラーの初期化
$errors = array();

// 何ページ目を開いているか決める
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

// ページネーション
const CONTENT_PER_PAGE = 5;

// -1などのページ数として不正な値を渡された場合の対策
$page = max($page, 1);

// ヒットしたレコードの数を取得するSQL
$sql_count = "SELECT COUNT(*) AS `cnt` FROM `friends` WHERE `user_id` = ?";
$data = array($signin_user['id']);
$stmt_count = $dbh->prepare($sql_count);
$stmt_count->execute($data);

$record_cnt = $stmt_count->fetch(PDO::FETCH_ASSOC);

// 取得したページ数を1ページあたりに表示する件数で割って何ページが最後になるか取得
$last_page = ceil($record_cnt['cnt'] / CONTENT_PER_PAGE);

// echo "<pre>";
// var_dump($record_cnt['cnt']);
// echo "</pre>";
// die();

// 最後のページより大きい値を渡された場合の対策
$page = min($page, $last_page);

$start = ($page - 1) * CONTENT_PER_PAGE;


// 最終的な結果を入れる配列の用意
$results = [];
// 友達一覧の結果を入れる配列の用意
$friends = [];
/// 友達一覧を取得する処理
if (isset($_GET['search_word'])) {
    $sql = 'SELECT * FROM `friends` WHERE `user_id` = ? AND `friends_name` LIKE "%" ? "%" ORDER BY `created` DESC LIMIT '. CONTENT_PER_PAGE .' OFFSET ' . $start;
    $data = [$signin_user['id'],$_GET['search_word']];
} else {
    $sql = 'SELECT * FROM `friends` WHERE `user_id` = ? ORDER BY `created` DESC LIMIT '. CONTENT_PER_PAGE .' OFFSET ' . $start;
    $data = array($signin_user['id']);
}
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

while (1) {
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    if($rec == false) {
        break;
    }
    $friends[] = $rec;
}

// 友達一覧を繰り返す処理
foreach($friends as $friend){
    // プレゼントを取得する処理
    $sql = 'SELECT * FROM `presents` WHERE `friend_id`= ? LIMIT 4 OFFSET 0';

    $data = array($friend['id']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    while (1) {
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if($rec == false) {
            break;
        }
        // 取得できたプレゼントを$friendに付け加える
        $friend['present'][] = $rec;
    }
    // 結果に格納
        $results[] = $friend;
}



?>

<!DOCTYPE html>
<html class="no-js"> 
<head>
    <meta charset="utf-8">
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
    <!-- Modernizr JS -->
    <script src="assets/js/modernizr-2.6.2.min.js"></script>
</head>
<body>
<?php include("nav-var.php"); ?>



<!-- ヘッダー始まり -->
    <header id="fh5co-header" class="fh5co-cover fh5co-cover-sm" role="banner" style="background-image:url(assets/images/background-image1.jpg);" style="background-size: 100%;">
        <div class="overlay"></div>
        <div class="fh5co-container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <div class="display-t">
                        <div class="display-tc animate-box" data-animate-effect="fadeIn">
                            <h1>Whose present</h1>
                            <h2>do you search?</h2>
                            <form method="GET" action="" class="navbar-form navbar-center" role="search">
                                <div class=“input-group”>
                                <input type=“text” name="search_word" placeholder=“友達の名前で検索できます“ style="width:300px; height: 30px;">
                                <span class=“input-group-btn”>
                                    <button class=“btn btn-primary"  style="color: #F14E95">Search</button>
                                </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
<!-- ヘッダー終わり -->
    <div class="body">
        <div class="block" style="margin-top: 100px"></div>
            <div class="container">
                <div class="row">
                    <div class="flame">
                        <?php foreach($results as $result): ?>
                                <section class="profile clearfix" style="display: inline-block;">
                                    <a href="list.php?id=<?php echo $result["id"]; ?>" class="btn btn-primary"><?php echo $result["friends_name"]; ?></a>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <a href="list.php?id=<?php echo $result["id"];?>">
                                                    <img src="friend_profile_image/<?php echo $result['friend_img']; ?>" class="piture-ajust img-profile " style="width: 200px; height: 180px; border-radius: 50%;" link="list.php">
                                                </a>
                                            </div>
                                            <?php foreach($result['present'] as $v): ?>
                                                <div class="col-sm-2" ><img src="present_image/<?php echo $v['img_name']; ?>" class="present-image" style="width: 180px; height: 150px; margin-top: 20px; border-radius: 10%;">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                            </section>
                            <?php endforeach; ?>
                        <!-- ページネーション -->
                        <div aria-label="Page navigation">
                            <ul class="pager">
                                <?php if ($page == 1): ?>
                                    <li class="previous disabled"><a><span aria-hidden="true">&larr;</span> Pre</a></li>
                                <?php else: ?>
                                    <li class="previous"><a href="home.php?page=<?= $page - 1; ?>"><span aria-hidden="true">&larr;</span> Pre</a></li>
                                <?php endif; ?>
                                <?php if ($page == $last_page): ?>
                                    <li class="next disabled"><a> Next<span aria-hidden="true">&rarr;</span></a></li>
                                <?php else: ?>
                                    <li class="next"><a href="home.php?page=<?= $page + 1; ?>">Next <span aria-hidden="true">&rarr;</span></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
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
<script src="assets/js/main.js"></script>

</body>
</html>

