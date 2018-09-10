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

//エラーの初期化
$errors = array();

//editボタンを押した時に書籍のデータが更新される
if(!empty($_POST)){
    // var_dump($_POST['id']);
    // die();
    $update_sql = "UPDATE `books` SET `title` = ?,`reason` = ?,`updated` = NOW() WHERE `id` = ? ";
    $data = array($_POST['title'],$_POST['reason'],$_POST['id']);
    $stmt = $dbh->prepare($update_sql);
    $stmt->execute($data);

    header('Location:home.php');
    exit();
}

//何ページ目を開いているか決める
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

//9冊ずつ表示し、それを超えたらページネーションできる
const CONTENT_PER_PAGE = 9;

// -1などのページ数として不正な値を渡された場合の対策
$page = max($page, 1);

// ヒットしたレコードの数を取得するSQL
$sql_count = "SELECT COUNT(*) AS `cnt` FROM `books`";
$stmt_count = $dbh->prepare($sql_count);
$stmt_count->execute();

$record_cnt = $stmt_count->fetch(PDO::FETCH_ASSOC);

//取得したページ数を1ページあたりに表示する件数で割って何ページが最後になるか取得
$last_page = ceil($record_cnt['cnt'] / CONTENT_PER_PAGE);

// 最後のページより大きい値を渡された場合の対策
$page = min($page, $last_page);

$start = ($page - 1) * CONTENT_PER_PAGE;


/// 書籍一覧を取得する処理
if (isset($_GET['search_word'])) {
    $sql = 'SELECT * FROM `books` WHERE `title` LIKE "%" ? "%" ORDER BY `created` DESC LIMIT '. CONTENT_PER_PAGE .' OFFSET ' . $start;
    $data = array($_GET['search_word']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
} else {
    $sql = 'SELECT * FROM `books` ORDER BY `created` DESC LIMIT '. CONTENT_PER_PAGE .' OFFSET ' . $start;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
}

$books = array();
while (1) {
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    if($rec == false) {
        break;
    }
    $books[] = $rec;

    // var_dump($books);
    // die();

// いいね済みかどうかの確認
    $like_flg_sql = 'SELECT * FROM `likes` WHERE `user_id` = ? AND `book_id` = ?';

    $like_flg_data = [$signin_user['id'], $rec['id']];

    $like_flg_stmt = $dbh->prepare($like_flg_sql);
    $like_flg_stmt->execute($like_flg_data);

    $is_liked = $like_flg_stmt->fetch(PDO::FETCH_ASSOC);

    // 三項演算子 条件式 ? trueだった場合 : falseだった場合
    $books["is_liked"] = $is_liked ? true : false;

}

// echo "<pre>";
// var_dump($books['id']['img_name']);
// echo "<pre>";
// die();

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
    <header id="fh5co-header" class="fh5co-cover fh5co-cover-sm" role="banner" style="background-image:url(assets/images/bookshelf.jpg);" style="background-size: 100%;">
        <div class="overlay"></div>
        <div class="fh5co-container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <div class="display-t">
                        <div class="display-tc animate-box" data-animate-effect="fadeIn">
                            <h1>what book</h1>
                            <h2>do you search?</h2>
                            <form method="GET" action="home.php" class="navbar-form navbar-center" role="search">
                                <div class="input_group">
                                    <input type=“text” name="search_word" placeholder=“書籍の名前で検索できます“ style="width:300px; height: 30px;">
                                    <span class=“input-group-btn”>
                                    <button class=“btn"  style="color: saddlebrown;">Search</button>
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
<!-- おすすめ書籍投稿一覧-->
    <div class="container">
        <div class="row row-bottom-padded-md " id="give-picture">
            <div class="col-md-12">
                <h1 class="text-center list">Posted List</h1>
                <div class="row">
                    <?php foreach ($books as $book): ?>
                        <div class="give">
                            <div class="col-xs-4">
                                <a data-target="book_<?php echo $book['id']?>" class="modal-open" >
                                    <img src="book_image/<?php echo $book['img_name']?>" class="picture-size" style="width:300px; height:400px; border-radius: 5%; margin: 10px; ">
                                </a>
                            </div>
                            <!-- モーダル -->
                            <div id="book_<?php echo $book['id']?>" class="modal-content" style="width: 800px; height: 500px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <br><br><img src="book_image/<?php echo $book['img_name']?>" class="picture-size" style="border-radius: 5%;">
                                    </div>
                                    <div class="col-md-6">
                                        <form class="form-group" method="post" action="home.php">
                                            <ul class="text-left" >
                                                <p>Book Title</p>
                                                <li><textarea name="title" class="form-control"><?php echo $book['title']?></textarea></li>
                                                <p>Detail</p>
                                                <li><textarea name="reason" class="form-control"><?php echo $book['reason']?></textarea></li>
                                            </ul>
                                            <div class="btn_user">
                                                <span hidden class="book_id" ><?= $book["id"] ?></span>
                                                <?php if ($book['is_liked']): ?>
                                                <button class="btn btn-default btn-xs js-unlike">
                                                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                                <span>いいねを取り消す</span>
                                                </button>
                                                <?php else: ?>

                                                <button class="btn btn-default btn-xs js-like">
                                                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                                <span>いいね!</span>
                                                </button>
                                                 <?php endif; ?>
                                                <span>いいね数 : </span>
                                                <span class="like_count">100</span>
                                            </div>
                                            <?php if ($book["user_id"] == $signin_user["id"] ): ?>
                                            <div class="btn_user">
                                                <input type="hidden" name="id" value="<?php echo $book['id']?>" >
                                                <input type="submit" class="btn btn-primary" value="edit">
                                                <a onclick="return confilm('ほんとに消すの？');" href="book_delete.php?id=<?php echo $book['id']?>" class="btn btn-danger btn-sm">delete</a>
                                            </div>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ページネーション -->
    <div aria-label="Page navigation " class="page-nation">
        <ul class="pager nation">
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
<script src="assets/js/script.js"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/magnific-popup-options.js"></script>
<!-- Main -->
<script src="assets/js/main.js"></script>
<script src="assets/js/app.js"></script>

</body>
</html>

