<!-- <?php
session_start();
require('dbconnect.php');

//サインインユーザー情報取得
$sql = 'SELECT * FROM `users` WHERE `id` =?';
$data = array($_SESSION['id']);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

// エラーの初期化
$errors = array();


$gives = [];
$takes = [];
$wants = [];

// editボタンを押した時にプレゼントのデータが更新される
if(!empty($_POST)){
    // var_dump($_POST['id']);
    // die();

    $friend_id = $_POST['friend_id'];
    $update_sql = "UPDATE `presents` SET `name` = ?,`date` = ?,`detail` = ? WHERE `id` = ? ";
    $data = array($_POST['name'],$_POST['date'],$_POST['detail'],$_POST['id']);
    $stmt = $dbh->prepare($update_sql);
    $stmt->execute($data);

    // var_dump($_POST['id']);
    // die();

    header('Location:list.php?id='.$friend_id);
    exit();
}

//DBからプレゼントデータ取得
if (isset($_GET['search_word'])) {
    $id = $_GET['id'];
    $sql = 'SELECT * FROM `presents` WHERE `friend_id` = ? AND (`name` OR `detail` LIKE "%" ? "%")';
    $data = array($id,$_GET['search_word']);
} else{
    $sql = 'SELECT * FROM `presents` WHERE `friend_id` = ?';
    $data = array($_GET['id']);
}
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    $presents = array();
    
        while (1) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec == false) {
                break;
            }

            if ($rec['which'] == 'give') {
                $gives[] = $rec;
            } elseif ($rec['which'] == 'take') {
                $takes[] = $rec;
            } elseif ($rec['which'] == 'want') {
                $wants[] = $rec;
            }

            $presents[] = $rec;
        }
    // echo "<pre>";
    // var_dump($wants);
    // echo "<pre>";
    // die();

// DBから友達のデータを取得する処理
$sql = 'SELECT * FROM `friends` WHERE `id` = ?';
$data = array($_GET['id']);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$friends = $stmt->fetch(PDO::FETCH_ASSOC);






?> -->
<!DOCTYPE html>
<html class="no-js"> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Present Box</title>
   <meta name="viewport" content="width=580">
    <meta name="description" content="Free HTML5 Template by FREEHTML5.CO">
    <meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive">
    <meta name="author" content="FREEHTML5.CO">
    <link rel="icon" type="images/favicon.png" href="assets/images/favicon.png"”>
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
<?php include('nav-var.php'); ?>

    <div id="fh5co-gallery" class="fh5co-section-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center fh5co-heading animate-box">
                    <span><?php echo $friends['birthday']; ?></span>
                    <h2><?php echo $friends['friends_name']; ?></h2>
                    <form method="GET" action="" class="" role="search">
                        <div class="form-group">
                            <input type="text" style="width:200px"  name="search_word"  placeholder="プレゼントを検索">
                            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                            <span class="form-group">
                                <button  class="btn square_btn">Serch</button>
                            </span>
                        </div>
                    </form>
                    <a href="list_make.php?id=<?php echo $friends["id"]; ?>" class="btn btn-primary">register present</a>
                </div>
            </div>

            <div class="container">
                <div class="row">
                      <ul class="nav nav-tabs">
                        <li class="col-xs-2 give_1 active"><a>友達にあげたもの</a></li>
                        <li class="col-xs-2 get_1"><a>友達からもらったもの</a></li>
                        <li class="col-xs-2 want_1"><a>友達がほしいもの</a></li>
                      </ul>
                      <br>
                </div>
            </div>

            <!-- あげたもの -->
        <div class="container">
            <div class="row row-bottom-padded-md " id="give-picture">
                <div class="col-md-12">
                    <h1 class="text-center">you gave these presents</h1>
                    <div class="row">
                        <?php foreach ($gives as $give): ?>
                            <div class="give">
                                <div class="col-xs-4">
                                    <a data-target="give_<?echo $give['id']?>" class="modal-open" >
                                        <img src="present_image/<?php echo $give['img_name']; ?>" class="picture-size" style="width:300px; height:300px; border-radius: 5%; margin: 10px; ">
                                    </a>
                                </div>
                                <!-- モーダル -->
                                <div id="give_<?echo $give['id']?>" class="modal-content" style="width: 800px; height: 400px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <br><br><img src="present_image/<?php echo $give['img_name']; ?>" class="picture-size" style="border-radius: 5%;">
                                        </div>
                                        <div class="col-md-6" style="font-size: 25px; line-height: 4em;">
                                            <form class="form-group" method="post" action="list.php">
                                                <ul class="text-left" >
                                                    <li><textarea name="name" class="form-control"><?php echo $give['name'] ?></textarea></li>
                                                    <li><textarea name="date" class="form-control"><?php echo $give['date'] ?></textarea></li>
                                                    <li><textarea name="detail" class="form-control"><?php echo $give['detail'] ?></textarea></li>
                                                </ul>
                                                <div class="btn_user">
                                                    <input type="hidden" name="friend_id" value="<?php echo $give['friend_id'] ?>" >
                                                    <input type="hidden" name="id" value="<?php echo $give['id'] ?>" >

                                                    <input type="submit" class="btn btn-primary" value="edit">

                                                    <a onclick="return confilm('ほんとに消すの？');" href="list_delete.php?id=<?php echo $give["id"] ?>&friend=<?php echo $_GET['id'];?>" class="btn btn-danger btn-sm">delete</a>
                                                </div>
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


<!-- もらったもの -->
        <div class="container">
            <div class="row row-bottom-padded-md " id="get-picture">
                <div class="col-md-12">
                    <h1 class="text-center">you got these presents</h1>
                    <div class="row">
                        <?php foreach ($takes as $take): ?>
                            <div class="take">
                                <div class="col-xs-4 ">
                                    <a data-target="take_<?php $take['id'] ?>" class="modal-open">
                                        <img src="present_image/<?php echo $take['img_name']; ?>" class="picture-size" style="width:300px; height:300px; border-radius: 5%; margin: 10px; ">
                                    </a>
                                </div>
                                <!-- モーダル -->
                                <div id="take_<?php $take['id']?>" class="modal-content" style="width: 800px; height: 400px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <br><br><img src="present_image/<?php echo $take['img_name']; ?>" class="picture-size" style="border-radius: 5%;">
                                        </div>
                                        <div class="col-md-6" style="font-size: 25px; line-height: 4em;">
                                            <form class="form-group" method="post" action="list.php">
                                                <ul class="text-left" >
                                                    <li><textarea name="name" class="form-control"><?php echo $take['name'] ?></textarea></li>
                                                    <li><textarea name="date" class="form-control"><?php echo $take['date'] ?></textarea></li>
                                                    <li><textarea name="detail" class="form-control"><?php echo $take['detail'] ?></textarea></li>
                                                </ul>
                                                <div class="btn_user ">
                                                    <input type="hidden" name="friend_id" value="<?php echo $take['friend_id'] ?>" >
                                                    <input type="hidden" name="id" value="<?php echo $take['id'] ?>" >

                                                    <input type="submit" class="btn btn-primary" value="edit">

                                                    <a onclick="return confilm('ほんとに消すの？');" href="list_delete.php?id=<?php echo $take["id"] ?>&friend=<?php echo $_GET['id'];?>" class="btn btn-danger btn-sm">delete</a>
                                                </div>
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


<!-- ほしいもの -->

            <div class="row row-bottom-padded-md " id="want-picture">
                <div class="col-md-12">
                    <h1 class="text-center">your friend want these presents</h1>
                    <div class="row">
                        <?php foreach ($wants as $want): ?>
                            <div class="want">
                                <div class="col-xs-4 ">
                                    <a data-target="want_<?php $want['id']?>" class="modal-open">
                                        <img src="present_image/<?php echo $want['img_name']; ?>" class="picture-size" style="width:300px; height:300px; border-radius: 5%; margin: 10px; ">
                                    </a>
                                </div>
                                <!-- モーダル -->
                                <div id="want_<?php $want['id']?>" class="modal-content" style="width: 800px; height: 400px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <br><br><img src="present_image/<?php echo $want['img_name']; ?>" class="picture-size" style="border-radius: 5%;">
                                        </div>
                                        <div class="col-md-6" style="font-size: 25px; line-height: 4em;">
                                            <form class="form-group" method="post" action="list.php">
                                                <ul class="text-left" >
                                                    <li><textarea name="name" class="form-control"><?php echo $want['name'] ?></textarea></li>
                                                    <li><textarea name="date" class="form-control"><?php echo $want['date'] ?></textarea></li>
                                                    <li><textarea name="detail" class="form-control"><?php echo $want['detail'] ?></textarea></li>
                                                </ul>
                                                <div class="btn_user ">
                                                    <input type="hidden" name="friend_id" value="<?php echo $want['friend_id'] ?>" >
                                                    <input type="hidden" name="id" value="<?php echo $want['id'] ?>" >

                                                    <input type="submit" class="btn btn-primary" value="edit">

                                                    <a onclick="return confilm('ほんとに消すの？');" href="list_delete.php?id=<?php echo $want["id"] ?>&friend=<?php echo $_GET['id'];?>" class="btn btn-danger btn-sm">delete</a>
                                                </div>
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
    <!--    自分で作ったやつ -->
    <script src="assets/js/script.js"></script>
    <script src="assets/js/simplyCountdown.js"></script>
    <!-- Main -->
    <script src="assets/js/main.js"></script>
<!--     <script>
        var d = new Date(new Date().getTime() + 200 * 120 * 120 * 2000);

    // default example
    simplyCountdown('.simply-countdown-one', {
        year: d.getFullYear(),
        month: d.getMonth() + 1,
        day: d.getDate()
    });

    //jQuery example
    $('#simply-countdown-losange').simplyCountdown({
        year: d.getFullYear(),
        month: d.getMonth() + 1,
        day: d.getDate(),
        enableUtc: false
    });
    </script> -->

</body>
</html>

