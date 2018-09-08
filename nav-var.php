    <!-- ナビゲーション始まり -->
    <div class="fh5co-loader"></div>
        <div id="page">
            <nav class="fh5co-nav" role="navigation">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-4">
                            <div id="fh5co-logo"><a href="home.php">Book SNS</a></div>
                        </div>
                        <div class="col-xs-8 text-right menu-1">
                            <ul>
                                <li class="dropdown">
                                    <!-- ユーザーID取得 -->
                                    <span hidden id="signin-user"><? $signin_user['id']; ?></span>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="background-color: saddlebrown; color: white; "><img src="user_profile_img/<?php echo $signin_user['img_name']; ?>"><?php echo $signin_user['user_name']; ?><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="profile.php">my profile</a></li>
                                        <li><a href="signout.php">signout</a></li>
                                    </ul>
                                    <a href="post.php"><i class="fa fa-heart"></i>Post your recomend</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- ナビゲーション終わり -->
