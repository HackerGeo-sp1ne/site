<?php

    if (!isset($_SESSION)) {
        session_start();
    }

    if (Utils::is_user_logged()) {
        $result = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE `username` = ?");
        $result -> bind_param('s',$_SESSION['user']);
        $result -> execute();
        $rez = $result -> get_result();
        $user_data = $rez->fetch_assoc();
        if ($user_data) {
            $user_groups = json_decode($user_data['groups'], true);
            $result->close();

            $usernameeee = Session::exists("user");
            $mysqli->query("UPDATE SF_Users SET last_online = '".date('d-m-Y H:i:s')."' WHERE username = '$usernameeee' ");

            if (json_decode($user_data['banned'],true)['banned']) {
                session_start();
                session_unset();
                session_destroy();
                header("location: index.php"); exit;
            }
        } else {
           // session_start();
            session_unset();
            session_destroy();
            header("location: index.php"); exit;
        }
    }
    if (isset($_POST['logout'])) {
        Session::destroy();
    }
?>

<?php if (GLOBAL_PROMOTION > 0) {?>
    <div style="background-color:#BD0A0A;color:white;text-align:center;font-size:18px;padding:8px 0;">
        <a href="<?php echo SITE_URL."/";?>store.php">Cumpara ACUM cu <?php echo GLOBAL_PROMOTION;?>% reducere la orice produs dorit.</a>
    </div>
<?php }?>

<div id="adblock" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal fade show" id="large-Modal" tabindex="-1" role="dialog"
         style="z-index: 1050; display: block; padding-right: 20px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Global Announce</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (Utils::is_user_logged()) {?>

<style>

    .adblock{
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        padding: 8px 15px;
        background-color: #31333A;
        border: 3px solid var(--primary-color);
        z-index: 15;
    }
    h1.message{
        padding: 8px 15px;
        color: #FFF;
        border-radius: 15px;
        font-weight: 600;
        font-size: 2em;
        max-width: 20rem;
        text-align: center;
        margin: 1.5rem 0;
        background-color: red;
        line-height: 1.5;
    }
</style>


<div class="modal fade" id="adblock-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
            <h1 class="message">Adblock detected!</h1>
    </div>
</div>

<div id="add_balance" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal fade show" id="large-Modal" tabindex="-1" role="dialog" style="z-index: 1050; display: block; padding-right: 20px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Balance</h3>
                </div>
                <div class="modal-body">
                <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="col-sm-12">
                                    <center>
                                        <h4>Founds details</h4>
                                            Username: <span class="badge bg-orange"><?php echo Session::exists('user')?></span><br>
                                            Balance: <span class="badge bg-green"><?php echo Utils::balanceFormat($user_data['balance']);?>&euro;</span><br><br>
                                        <b>I AM NOT RESPONSIBLE FOR PAYMENT ELSEWHERE.</b><br>
                                        Any problem related to payment you can contact at discord (<b>HackerG ﾉ sp1nE#8389</b>) or make a <a href='<?php echo SITE_URL."/";?>404.php'><b>TICKET</b></a><br>
                                        You will receive an email when you make the payment
                                    </center>

                                    <form method="POST">
                                        <div class="row">
                                            <label class="col-md-3 app_style">
                                                <b style="font-weight: bold;">Balance Key</b>
                                            </label>
                                            <div class="col-md-9 app_style">
                                                <input required type="text" placeholder="Plase enter the key!" name="balance_key" class="form-control" />
                                            </div>
                                        </div>

                                        <br />
                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                            <button type="submit" name="submit" class="btn btn-info btn-block glow mr-sm-1 mb-1">ADD TO BALANCE</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="edit_profile" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal fade show" id="large-Modal" tabindex="-1" role="dialog" style="z-index: 1050; display: block; padding-right: 20px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Profile</h3>
                </div>
                <div class="modal-body">
                <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">

                                <div class="tabs-container">
                                    <ul class="nav nav-tabs customtab nav-fill">
                                        <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#tab-avatar" aria-expanded="true">Avatar</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-password" aria-expanded="false">Password</a></li>
                                    </ul>
                                </div>
                                <div class="tab-content">


                                    <div id="tab-avatar" class="tab-pane active show">
                                        <br>
                                        <form method="post" enctype="multipart/form-data">
                                            <center class="col-lg-12">
                                                <img src="<?php echo $user_data['avatar'];?>" class="img-circleeeeeeeeeeeeeeeeeeeee" width="150" /> <br />
                                                <br />
                                                    <div class="row">
                                                        <label class="col-md-3 app_style">
                                                            <b style="font-weight: bold;">Select New Avatar</b>
                                                        </label>

                                                        <div class="col-md-9 app_style">
                                                            <input require type="file" name="fileToUpload" id="fileToUpload">
                                                        </div>

                                                        <button type="submit" value='Upload Image' name="avatar_submit" style="margin-top:10px;" class="btn btn-success btn-block glow mr-sm-1 mb-1">Save</button>
                                                </div>
                                            </center>
                                        </form>

                                    </div>

                                    <div id="tab-password" class="tab-pane">
                                        <br>
                                        <form method="POST">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <label class="col-md-3 app_style">
                                                        <b style="font-weight: bold;">Actual Password</b>
                                                    </label>

                                                    <div class="col-md-9 app_style">
                                                        <input required type="password" placeholder="Password" class="form-control" name="actual_password" />
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <label class="col-md-3 app_style">
                                                        <b style="font-weight: bold;">New Password</b>
                                                    </label>

                                                    <div class="col-md-9 app_style">
                                                        <input required type="password" placeholder="Password" class="form-control" name="new_password" />
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <label class="col-md-3 app_style">
                                                        <b style="font-weight: bold;">New Password Repeat</b>
                                                    </label>

                                                    <div class="col-md-9 app_style">
                                                        <input required type="password" placeholder="Password" class="form-control" name="new_password2" />
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-sm-row flex-column justify-content-end">
                                                    <button type="submit" name="submit" style="margin-top:10px;" class="btn btn-success btn-block glow mr-sm-1 mb-1">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>


<header class="header">
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
                <div class="navbar-header">
                    <a href="<?php echo SITE_URL."/";?>index.php" class="navbar-brand d-none d-sm-inline-block">
                       
                    <!-- <style>
                            .wrapper {
                                font-size: 45px;
                                height: 2em;
                                width: 100%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                mix-blend-mode: screen;
                                
                                border-radius: 10px;
                                border-top-right-radius: 25px;
                                border-bottom-left-radius: 25px;
                                border: 2px solid var(--primary-color);
                                padding: 3px 12px;
                            }
                                                            
                            .wrapper span {
                            --color: #FDF5E6;
                            font-family: Impact, "Anton", Haettenschweiler, "Arial Narrow Bold", sans-serif;
                            font-weight: 700;
                            font-style: italic;
                            display: block;
                            position: absolute;
                            color: var(--color);
                            letter-spacing: -0.005em;
                            }
                            .wrapper span::before, .wrapper span::after {
                            content: attr(data-text);
                            display: block;
                            position: relative;
                            padding: 0 0.1em;
                            z-index: 1;
                            }
                            .wrapper span::before {
                            position: absolute;
                            -webkit-text-stroke: 0.1em black;
                            z-index: 0;
                            }
                            .wrapper span:first-child {
                            transform: translate(-0.755em, -0.35em);
                            }
                            .wrapper span:nth-child(2) {
                                --color: var(--primary-color);
                            transform: translate(0.155em, 0.35em);
                            }
                            .wrapper span:last-child {
                                font-size: 25px;
                            --color: var(--primary-color);
                            transform: translate(1.555em, -0.35em);
                            }
                        </style>
                        <div class="wrapper">
                            <span data-text="Stay"></span>
                            <span data-text="Frosty"></span>
                            <span data-text=".com"></span>
                        </div>-->          
                        
                        <div class="brand-text d-none d-lg-inline-block"><span class="title-font"><span style="color:var(--primary-color);font-size:25px;">S</span>TAY<span style="color:var(--primary-color);font-size:25px;">F</span>ROSTY</span></div>
                    </a>
                    <a id="toggle-btn" style="color:var(--primary-color) !important;"  href="#" ><i style="font-size:22px;" class="fa fa-bars"></i></a>
                </div>

                <div class="color-switcher">
                    <div class="colorbuttons">
                        <li data-color="default" onclick="changecolor(this)" class="defaultbtn"></li>
                        <li data-color="green" onclick="changecolor(this)" class="greenbtn"></li>
                        <li data-color="blue" onclick="changecolor(this)" class="bluebtn"></li>
                        <li data-color="orange" onclick="changecolor(this)" class="orangebtn"></li>
                        <li data-color="purple" onclick="changecolor(this)" class="purplebtn"></li>
                        <li data-color="yellow" onclick="changecolor(this)" class="yellowbtn"></li>                    
                        <li data-color="rainbow" onclick="changecolor(this)" class="rainbowbtn"></li>
                        <p>Change theme color</p>
                    </div>
                </div>

                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">

                    <!-- Logout    -->
                    <li class="nav-item">

                        <?php
                        if (Utils::is_user_logged()) {
                            echo '
                            <form action="" method="post">
                                <button type="submit" name="logout" class="nav-link logout butonas"> <span class="d-none d-sm-inline";>Logout</span><i class="fa fa-sign-out"></i></button>
                            </form>
                            ';
                        } else {
                            echo '<button onclick="window.location = `login.php`" class="nav-link logout butonas plinut"> <span class="d-none d-sm-inline";>Login</span><i class="fa fa-sign-out"></i></button>';
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="page-content d-flex align-items-stretch">
    <nav class="side-navbar">
        <div class="sidebar-header d-flex align-items-center">
            <?php
                if (Utils::is_user_logged()) {
                    echo '<div class="avatar"><img src="'.$user_data["avatar"].'" alt="'.Session::exists("user").'" class="img-circleeeeeeeeeeeeeeeeeeeee" style="height: 55px;width: 55px;"/></div>';
                } else {
                    echo '<div class="avatar"><img src="'.(isset($use_directory) ? "../" : "").'template\img\avatars\no-avatar.png" alt="..." class="img-circleeeeeeeeeeeeeeeeeeeee" style="height: 55px;width: 55px;"/></div>';
                }
            ?>

            <div class="title">
                <?php
                    if (Utils::is_user_logged()) {
                        echo '
                        <h1 style="color: var(--color-principaly) !important;" class="h4"><a href="'.SITE_URL.'/profile.php?user='.Session::exists("user").'">'.$_SESSION['user'].'</a></h1>
                        <p><span style="color:var(--color-principaly) !important;">Your ip:</span> <b style="color:var(--primary-color);">'.Utils::get_ip().'</b></p>
                        <p><span style="color:var(--color-principaly) !important;">Balance:</span> <b style="color:#3ADD00;">'.Utils::balanceFormat($user_data['balance']).'&euro;</b></p>';
                    } else {
                        echo '<h1 style="color: var(--color-principaly) !important;" class="h4">Guest</h1>
                        <p>Please <a href="'.SITE_URL.'/login.php" style="color:var(--primary-color);">login</a></p>
                        <p>or <a href="'.SITE_URL.'/register.php" style="color:var(--primary-color);">register</a></p>';
                    }
                ?>
            </div>
        </div>
        <?php
            if (Utils::is_user_logged()) {
                if (Utils::is_user_permission($user_groups['groups'],"staff")) {
               // if( $user_groups['groups']["developer"] || $user_groups['groups']["admin"] || $user_groups['groups']["helper"] ){
        ?>
                <span class="heading">Administration</span>
                <ul class="list-unstyled">
                    <?php
                        if (Utils::is_user_permission($user_groups['groups'],"administration.tools")) {
                    ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="ti-help" style="color:#F5A817;"></i>Tools </a>
                            <div class="dropdown-menu btn-dark">
                                <a class="dropdown-item" href="#" style="font-weight:bold;">ViTSETe</a>
                                <a class="dropdown-item" href="#">X</a>
                            </div>
                        </li>
                    <?php
                        }
                    ?>
                    <?php
                        if (Utils::is_user_permission($user_groups['groups'],"administration.problems")) {
                    ?>
                    <li class="<?php if ($page=="problems") echo 'active';?>">
                        <a href="<?php echo SITE_URL."/";?>problems.php"> <i class="fa fa-exclamation-triangle" style="color:#DC3545;"></i>Problems
                            <span class="pull-right">

                                <?php
                                    $numar_erori=0;
                                    $fisier = file_get_contents((isset($use_directory) ? "." : "")."./template/problems.json");
                                    if ($fisier) {
                                        $file_data = json_decode($fisier, true);
                                        foreach($file_data['errors'] as $eroare) {
                                            $numar_erori++;
                                        }
                                    }
                                ?>
                                <span class="text bg-danger"><?php echo $numar_erori?></span>
                            </span>
                        </a>
                    </li>
                    <?php
                        }
                    ?>
                </ul>
        <?php
                }
            }
        ?>

        <span class="heading">Navigation</span>
        <ul class="list-unstyled">
            <li class="<?php if ($page=="index") echo 'active';?>">
                <a href="<?php echo SITE_URL."/";?>index.php"> <i class="ti-home" style="color:#0BCA57 !important;"></i>Dashboard </a>
            </li>
            <?php
                $r_query = $mysqli->query("SELECT * FROM `SF_Users`");
                $staff=0;
                while ($row = $r_query->fetch_assoc()) {
                    $datatable = json_decode($row['groups'], true);
                    if( $datatable['groups']["developer"] || $datatable['groups']["admin"] || $datatable['groups']["helper"] ){
                        $staff++;
                    }
                }
                $r_query->close();
            ?>

            <li class="<?php if ($page=="search") echo 'active';?>">
                <a href="<?php echo SITE_URL."/";?>search.php"> <i class="ti-search" style="color:#FC4245 !important;"></i>Search</a>
            </li>

            <li class="<?php if ($page=="staff") echo 'active';?>">
                <a href="<?php echo SITE_URL."/";?>staff.php"> <i class="ti-shield" style="color:#0090E7 !important;"></i>Staff
                    <span class="pull-right">
                        <span class="text" style="background-color: #0090E7;color:color-yiq(#0090E7);"><?php echo ($staff-1)."/".(MAX_admin+MAX_helper);?></span>
                    </span>
                </a>
            </li>

            <li class="<?php if ($page=="store") echo 'active';?>">
                <a href="<?php echo SITE_URL."/";?>store.php"> <i class="ti-money" style="color:#0BCA57 !important;"></i>Store</a>
            </li>

            <li class="">
                <a href="https://www.instagram.com/hacker.geo/" target="_blank"> <i class="ti-instagram" style="color:#E65468 !important;"></i><font style="color:#E65468;font-weight:bold;">Instagram</font></a>
            </li>
        </ul>

        <span class="heading">Gambling</span>
        <ul class="list-unstyled">
            <li class="<?php if ($page=="r_number") echo 'active';?>">
                <a href="<?php echo SITE_URL."/";?>gambling/random_number.php"> <i class="fa fa-spinner" style="color:#9457EB !important;"></i><font style="color:#9457EB;font-weight:bold;">Random Number</font>
                    <span class="pull-right">
                        <span class="text" style="background-color: var(--primary-color);color:color-yiq(#0090E7);">NEW</span>
                    </span>
                </a>
            </li>
            <li class="<?php if ($page=="c_flip") echo 'active';?>">
                <a href="<?php echo SITE_URL."/";?>gambling/coin_flip.php"> <i class="fa fa-money" style="color:#20B2AA !important;"></i><font style="color:#20B2AA;font-weight:bold;">Coin Flip</font>
                    <span class="pull-right">
                        <span class="text" style="background-color: var(--primary-color);color:color-yiq(#0090E7);">NEW</span>
                    </span>
                </a>
            </li>
            <li class="<?php if ($page=="lottery") echo 'active';?>">
                <a href="#lottery_c" data-toggle="collapse" aria-expanded="false"> <i class="fa fa-star" style="color:#95EB00 !important;"></i><font style="color:#95EB00;font-weight:bold;">Lottery</font></a>
                <ul aria-expanded="true" id="lottery_c" class="collapse" style="background-color:#23272b;">
                    <a href="<?php echo SITE_URL."/";?>gambling/lottery.php" style="font-weight:bold;">Lottery 7/40
                        <span class="pull-right">
                            <span class="text" style="background-color: var(--primary-color);color:color-yiq(#0090E7);">NEW</span>
                        </span>
                    </a>
                    <a href="<?php echo SITE_URL."/";?>gambling/lottery20.php">Lottery 20/90
                        <span class="pull-right">
                            <span class="text" style="background-color: var(--primary-color);color:color-yiq(#0090E7);">NEW</span>
                        </span>
                    </a>                
                </ul>
            </li>
        </ul>

        <?php if (Utils::is_user_logged()) {?>
            <span class="heading">Profile</span>
            <ul class="list-unstyled">
                <li class="<?php if ($page=="profile") echo 'active';?>">
                    <a href="#profile_c" data-toggle="collapse" aria-expanded="false"> <i class="ti-user" style="color:#0BCA57 !important;" ></i>Profile</a>
                    <ul aria-expanded="true" id="profile_c" class="collapse" style="background-color:#23272b;">
                        <a href="<?php echo SITE_URL."/";?>profile.php" style="font-weight:bold;">View Profile</a>
                        <a href="<?php echo SITE_URL."/";?>referral.php">Referral</a>
                        <a href="#edit_profile" data-toggle="modal" data-target="#edit_profile">Edit</a>
                        <a href="#add_balance" data-toggle="modal" data-target="#add_balance">Add Balance</a>
                        <a href="#buy_balance" data-toggle="modal" data-target="#buy_balance">Buy Balance</a>
                    </ul>
                </li>
            </ul>
            <!--
                <ul class="list-unstyled">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user" style="color:var(--primary-color) !important;"></i>Profile </a>
                        <div class="dropdown-menu btn-dark">
                            <a class="dropdown-item" href="profile.php" style="font-weight:bold;">View Profile</a>
                            <a class="dropdown-item" href="referral.php">Referral</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#edit_profile" href="#edit_profile">Edit</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#add_balance" href="#add_balance">Add Balance</a>
                        </div>
                    </li>
                </ul>
            -->
                <div class="col-lg-12" style="font-size:10px;">
                    <?php

                        if(isset($_POST["avatar_submit"])) {
                            $file = $_FILES['fileToUpload'];
                            if (stripos($file['type'], 'image') !== false) {
                                $ext_a = explode('.',$file['name']);
                                $ext = $ext_a[count($ext_a) - 1];
                                if($ext == "jpg" || $ext == "png" || $ext == "jpeg") {
                                    if ($file["size"] < MAX_UPLOAD_SIZE) {
                                        if ($file['error'] == 0) {
                                         //   $file_name = $_SESSION['user'].".".$ext;
                                         //   move_uploaded_file($file['tmp_name'],"template/img/avatars/".$file_name);

                                            $image = $file['tmp_name'];
                                            $pvars   = array('image' => base64_encode(fread(fopen($image, "r"), filesize($image))));

                                            $ch = curl_init();
                                            curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
                                            curl_setopt($ch, CURLOPT_POST, TRUE);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID bfd4ae5bac8c003'));
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $pvars);
                                            $reply = curl_exec($ch);
                                            curl_close($ch);
                                            $reply = json_decode($reply);

                                            if (isset($reply->data->link)) {
                                                $query = $mysqli->prepare("UPDATE SF_Users SET avatar = ? WHERE username = ?");
                                                $query -> bind_param('ss',$reply->data->link,$_SESSION['user']);
                                                $query -> execute();
                                                $query -> close();

                                                $utils->add_log(Session::exists("user"),"Si-a schimbat avatarul!");

                                                echo '<meta http-equiv = "refresh" content = "2; url = profile.php" />';
                                                echo '
                                                    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    '.$file['name'] .' was successfully uploaded!
                                                    </div>
                                                ';

                                            } else {
                                                echo '
                                                <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                Upload ERROR. Imgur problem.
                                                </div>
                                            ';
                                            }
                                        }
                                    } else {
                                        echo '
                                            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            The picture is too big.
                                            </div>
                                        ';
                                    }
                                } else {
                                    echo '
                                        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        Only JPG, JPEG, PNG.
                                        </div>
                                    ';
                                }
                            } else {
                                echo '
                                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    The file is not an image.
                                    </div>
                                ';
                            }
                        }


                        if (isset($_POST['balance_key'])) {
                            $usr = new User();
                            $usr->add_balance(Session::exists("user"),$_POST['balance_key']);
                        }


                        if (isset($_POST['new_password']) && isset($_POST['actual_password']) && isset($_POST['new_password2'])) {
                            include_once('classes/User.php');
                            $usr = new User();
                            $usr->change_password( array('username' => Session::exists("user"),'actual_password' => $_POST['actual_password'],'new_password' => $_POST['new_password'],'new_password2' => $_POST['new_password2']) );

                            if (!empty($usr->errors())) {
                                ?> 
                                <div class="col-lg-16">     
                                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                        role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <?php 
                                            foreach($usr->errors() as $error){  
                                                echo $error."<br>";
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php 
                            }
                        }
                    ?>
                </div>
        <?php }?>
    </nav>
