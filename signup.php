<?php
session_start();
require('dbconnect.php');
date_default_timezone_set('Asia/Manila');

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite'){
    $_POST['users_name'] = $_SESSION['register']['name'];
    $_POST['users_email'] = $_SESSION['register']['email'];
    $_POST['users_password'] = $_SESSION['register']['password'];

    $errors['rewrite'] = true;
}

$name = '';
$email = '';
$errors = array();

if(!empty($_POST)){
    $name = $_POST['users_name'];
    $email = $_POST['users_email'];
    $password = $_POST['users_password'];

    if($name == ''){
        $errors['name'] = 'blank';
    }

    if($email == ''){
        $errors['email'] = 'blank';
    }

    $count = strlen($password);
    if($password == ''){
        $errors['password'] = 'blank';
    }elseif ($count < 4 || 16 < $count) {
        $errors['password'] = 'length';
    }

    // 画像名を取得
    $file_name = '';
    if(!isset($_GET['action'])){
        $file_name = $_FILES['users_img_name']['name'];
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

    // 重複チェック
    $sql = 'SELECT * FROM `users` WHERE email = ?';
    $data = array($email);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    $hoge = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($hoge)) {
        $errors['email'] = 'double';
        ;

    }


    //エラーがなかった時の処理
    if(empty($errors)){
        $date_str = date('YmdHis');
        $submit_file_name = $date_str . $file_name;

        move_uploaded_file($_FILES['users_img_name']['tmp_name'],'user_profile_image/'.$submit_file_name);

        $_SESSION['register']['name'] = $_POST['users_name'];
        $_SESSION['register']['email'] = $_POST['users_email'];
        $_SESSION['register']['password'] = $_POST['users_password'];

        $_SESSION['register']['img_name'] = $submit_file_name;
        header('Location: check.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="images/favicon.png" href="assets/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <!-- Website Font style -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/signup.css">
    <link rel="stylesheet" media="(max-width: 640px)" href="assets/css/mobile.css">
    <title>BookSNS</title>
</head>
<body background="assets/images/bookshelf.jpg">
    <div class="container">
        <div class="row main">
            <div class="panel-heading">
               <div class="panel-title text-center">
                    <h1 class="title">register</h1>
                </div>
            </div> 
            <div class="main-login main-center">
                <form class="form-horizontal" method="POST" action="signup.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="cols-sm-2 control-label">Your Name</label>
                        <div class="cols-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="users_name" id="name"  placeholder="Enter your Name" value="<?php echo htmlspecialchars($name); ?>">
                            </div>
                            <?php if(isset($errors['name']) && $errors['name'] == 'blank'): ?>
                                    <p class="text-danger">Enter your name</p>
                            <?php endif;?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="cols-sm-2 control-label">Email</label>
                        <div class="cols-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                <input type="email" class="form-control" name="users_email" id="email"  placeholder="Enter your email adress" value="<?php echo htmlspecialchars($email); ?>">
                            </div>
                            <?php if(isset($errors['email']) && $errors['email'] == 'blank'): ?>
                                    <p class="text-danger">Enter your email adress</p>
                            <?php endif;?>
                            <?php if(isset($errors['email']) && $errors['email'] == 'double'): ?>
                                <p class="text-danger">Other user use the id already</p>
                            <?php endif;?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="cols-sm-2 control-label">Password</label>
                        <div class="cols-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                <input type="password" class="form-control" name="users_password" id="users_password"  placeholder="4〜16 letters Password">
                            </div>
                            <?php if(isset($errors['password']) && $errors['password'] == 'blank'): ?>
                                    <p class="text-danger">Enter your password</p>
                            <?php endif;?>
                            <?php if(isset($errors['password']) && $errors['password'] == 'length'): ?>
                                <p class="text-danger">please enter 4〜16 letters</p>
                            <?php endif; ?>
                            <?php if(!empty($errors)): ?>
                                <p class="text-danger">enter password again</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm" class="cols-sm-2 control-label">Confirm Password</label>
                        <div class="cols-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                <input type="password" class="form-control" name="confirm" id="confirm"  placeholder="Confirm your Password" oninput="CheckPassword(this)" >
                            </div>
                            <?php if(isset($errors['password']) && $errors['password'] == 'blank'): ?>
                                    <p class="text-danger">Enter your password</p>
                            <?php endif;?>
                            <?php if(!empty($errors)): ?>
                                <p class="text-danger">enter password again</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="img_name" class="cols-sm-2 control-label">profile image</label>
                        <div class="cols-sm-10">
                            <input type="file" name="users_img_name" id="img_name" accept="image/*" >
                            <?php if(isset($errors['img_name'])&& $errors['img_name'] == 'blank'): ?>
                                <p class="text-danger">enter your image</p>
                            <?php endif;?>
                            <?php if(isset($errors['img_name'])&& $errors['img_name'] == 'type'): ?>
                                <p class="text-danger">only 'jpg'.'png','gif' type</p>
                            <?php endif;?>
                        </div>
                    </div>

                    <div class="form-group ">
                        <button class="btn btn-primary btn-lg btn-block login-button">Register</button>
                    </div>
                    <script>
                      var form = document.forms[0];
                      form.onsubmit = function() {
                        // エラーメッセージをクリアする
                        form.password.setCustomValidity("");
                        // パスワードの一致確認
                        if (form.users_password.value != form.confirm.value) {
                          // 一致していなかったら、エラーメッセージを表示する
                          form.users_password.setCustomValidity("パスワードと確認用パスワードが一致しません");
                        }
                      };
                      // 入力値チェックエラーが発生したときの処理
                      form.addEventListener("invalid", function() {
                        document.getElementById("errorMessage").innerHTML = "入力値にエラーがあります";
                      }, false);
                    </script>
                    <div class="back">
                        <a href="index.php" class="btn btn-default">&laquo;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;back</a></font>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/signup.js"></script>
</body>
</html>