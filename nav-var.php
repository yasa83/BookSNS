    <!-- ナビゲーション始まり -->
    <div class="fh5co-loader"></div>
        <div id="page">
            <nav class="fh5co-nav" role="navigation">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-4">
                            <div id="fh5co-logo"><a href="home.php">Present Box</a></div>
                        </div>
                        <div class="col-xs-8 text-right menu-1">
                            <ul>
                                <li class="dropdown">


                                
                                    <!-- ユーザーID取得 -->
                                    <span hidden id="signin-user"><? $signin_user['id']; ?></span>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="background-color: #F14E95; color: white; height: 20px;"><img src="user_profile_img/<?php echo $signin_user['img_name']; ?>" style="width:20px" class="img-circle"><?php echo $signin_user['users_name']; ?><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="want.php">my want list</a></li>
                                        <li><a href="home.php">my page</a></li>
                                        <li><a href="signout.php">signout</a></li>
                                    </ul>
                                    <a href="friend.php">add friend</a>

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- ナビゲーション終わり -->
