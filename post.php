<!DOCTYPE html>
<html class="no-js"> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BookSNS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Free HTML5 Template by FREEHTML5.CO" />
    <meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />
    <meta name="author" content="FREEHTML5.CO" />
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
    <header id="fh5co-header" class="fh5co-cover fh5co-cover-sm" role="banner" style="background-image:url(assets/images/want.jpg);">
        <div class="overlay"></div>
        <div class="container" style="padding-top:45px;">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">To present</h2>
                <form method="POST" action="list_make.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="checkbox-inline">
                            <input type="radio" name="check" value="give" checked="checked">友達にあげたもの
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" name="check" value="take">友達からもらったもの
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" name="check" value="want">友達がほしいもの
                        </label>
                        <?php if(isset($errors['check']) && $errors['check'] == 'blank'): ?>
                            <p class="text-danger">Choose one</p>
                        <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="present">Present</label>
                        <input type="text" name="input_present" class="form-control" value="" placeholder="商品名">
                        <?php if(isset($errors['present']) && $errors['present'] == 'blank'): ?>
                            <p class="text-danger">Enter present's name</p>
                        <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="date">date</label>
                        <input type="date" name="input_date" class="form-control" value="" placeholder="もらった・あげた日付を登録してください">
                        <?php if(isset($errors['date']) && $errors['date'] == 'blank'): ?>
                            <p class="text-danger">Enter the date</p>
                        <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="detail">Detail</label>
                        <input type="text" name="input_detail" class="form-control" rows="10" placeholder="メモを入力してください" value="">
                    </div>
                    <div class="form-group">
                        <label for="img_name"></label>
                        <input type="file" name="input_img_name" id="image/*"
                        id="img_name">
                        <?php if(isset($errors['img_name'])&& $errors['img_name'] == 'blank'): ?>
                            <p class="text-danger">enter present's image</p>
                        <?php endif;?>
                        <?php if(isset($errors['img_name'])&& $errors['img_name'] == 'type'): ?>
                            <p class="text-danger">only 'jpg'.'png','gif' type</p>
                        <?php endif;?>
                    </div>
                        <input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
                    <br>
                    <ul class="nav navbar-nav navbar-left">
                        <li class="active"><a href="list.php?id=<?php echo $friend_id;?>" style="margin: 15px,background-color: black;">友達のページに戻る</a></li>
                    </ul>
                    <input type="submit" class="btn btn-primary" value="登録">
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

