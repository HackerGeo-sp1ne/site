<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php
        $title = "Login";
        $page = "login";
        include "config.php"; //CONFIG
        require "includes/head.inc.php"; // <HEAD>
        if (Utils::is_user_logged()) {
            header("location: index.php"); exit();
        }
    ?>

<body>
    <div class="page">
        <?php require "includes/navbar.inc.php"; //NAVBAR

            $login = new User();

            //if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['captcha_code']) && Session::exists('verify_code')) {
            if (isset($_POST['login']) && isset($_POST['login_key']) && isset($_POST['captcha_code']) && Session::exists('verify_code')) {
                if( $_POST['captcha_code'] == $_SESSION['verify_code'] ) {
                    $login->try_login( array('login_key' => $_POST['login_key']) );
//                    $login->try_login( array('username' => $_POST['username'], 'password' => $_POST['password']) );
                } else {
                    $login->addError('Incorrect code!');
                }
            }

            if (isset($_POST['send_email']) && isset($_POST['send_email_usr'])) {
                $result = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE `username` = ?");
                $result -> bind_param('s',$_POST['send_email_usr']);
                $result -> execute();
                $rez = $result -> get_result();
                $row = $rez->fetch_assoc();
                if ($row['verified']) {
                    require("includes/classes/Email.php");
                    $to = $_POST['send_email'];
                    $subject = "support@hackergeo.com";
                    $verify_code = generateString(30);

                    $email_message = '
                        <style>
                            .butonas {
                                border: none;
                                border-radius: 15px;
                                font-family:Poppins, sans-serif;
                                border: 3px solid #41ace0;
                                text-decoration: none;
                                background-color: #41ace0;
                                padding: 0.5rem 1rem;
                                color: white;
                            }
                            .butonas:hover {
                                transition: all 0.5s ease-in-out;
                                background-color: #41ace060;
                                color: white;
                                border-radius: 20px;
                            }
                        </style>
                        <td>
                            <table width="600" cellspacing="0" cellpadding="0" style="background-color: #2F333E;border:1px solid #28292F;border-radius:3px;margin:10px;margin-top:30px;">
                                <tbody>
                                <tr>
                                    <td align="center" style="overflow:hidden;border-top-left-radius:3px;border-top-right-radius:3px;">
                                        <img src="https://i.imgur.com/pOGKQGt.png" alt="StayFrosty" width="600" height="170">
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-size:1px;line-height:30px;" height="30">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td style="font-family:Poppins, sans-serify;font-size:28px;font-weight:bold;text-align:center;color:white;">E-mail Confirmation<br></td>
                                </tr>

                                <tr>
                                    <td style="font-size:1px;line-height:30px;" height="30">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td style="font-family:sans-serif;font-size:15px;color:#C2C2CC;line-height:24px;text-align:center;">Hey <b>.'.$row['username'].'.</b>,<span><p>It looks like you just signed up for <b>StayFrosty</b>, that’s awesome!<br>Can we ask you for email confirmation?<br><b>Just click the button bellow.</b></p></span></td>
                                </tr>

                                <tr>
                                    <td style="font-size:1px;line-height:40px;" height="40">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a rel="noreferrer" target="_blank" href="'.SITE_URL."/index.php?verify_email=".$verify_code.'" class="butonas">CONFIRM EMAIL ADRESS</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:1px;line-height:50px;" height="50">&nbsp;</td>
                                </tr>
                            </tbody>
                        </td>';

                    $emailsend = new Email();
                    if ($emailsend->send_Email($to,$subject,$email_message,$_POST['send_email_usr'])) {
                        $query = $mysqli->prepare("UPDATE SF_Users SET verified=? WHERE username = ?");
                        $query -> bind_param('ss',$verify_code,$_POST['send_email_usr']);
                        $query -> execute();
                        $query -> close();
                    } else {
                        echo 'Try again later...';
                    }
                }
                $result->close();
            }

            ?>

        <div class="content-inner">

            <section class="tables">
                <div class="container-fluid">

                    <?php if (!empty($login->errors())) { ?>
                    <div class="col-lg-16">
                        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                            role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <?php 
                                            foreach($login->errors() as $error){  
                                                echo $error."<br>";
                                            }
                                        ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="row bg-white has-shadow">
                        <div class="col-lg-12">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="h4">Enter details</h3>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
<!--                                    <div class="form-group">-->
<!--                                        <label class="form-control-label">Username</label>-->
<!--                                        <input required type="text" placeholder="Username" class="form-control"-->
<!--                                            value="--><?php //if(isset($_COOKIE["login_user"])) { echo $_COOKIE["login_user"]; } ?><!--"-->
<!--                                            name="username" />-->
<!--                                    </div>-->
<!--                                    <div class="form-group">-->
<!--                                        <label class="form-control-label">Password</label>-->
<!--                                        <input required type="password" id="password" placeholder="Password"-->
<!--                                            class="form-control"-->
<!--                                            value="--><?php //if(isset($_COOKIE["login_password"])) { echo $_COOKIE["login_password"]; } ?><!--"-->
<!--                                            name="password" />-->
<!--                                    </div>-->
                                    <div class="form-group">
                                        <label class="form-control-label"><i class="ti-lock" aria-hidden="true"></i>&nbsp;Key <small class="text-muted">You find on email</small></label>
                                        <input required type="text" placeholder="sf-xxxxxxxxxxxxxxxxxxxxxxxxx" class="form-control"
                                               value="<?php if(isset($_COOKIE["login_key"])) { echo $_COOKIE["login_key"]; } ?>"
                                               name="login_key" />
                                    </div>




                                    <div class="form-group">
                                        <label class="form-control-label">Verify Code</label></br>
                                        <img style="margin-bottom:10px;" src="includes/captcha.php">
                                        <input required type="number" id="password" placeholder="Input the code"
                                            class="form-control"
                                               name="captcha_code" />
                                    </div>

                                    <div class="custom-control custom-checkbox mr-sm-2 mb-3">
                                        <input onClick="showpassword()" type="checkbox" class="custom-control-input"
                                            id="checkbox1">
                                        <label class="custom-control-label" for="checkbox1" style="color:#C8C8C8;">Show
                                            Password</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-sm-2 mb-3">
                                        <input name="rememberme" type="checkbox" class="custom-control-input"
                                            id="checkbox0" <?php if(isset($_COOKIE["login_user"])) {echo 'checked';} ?>
                                            value="check">
                                        <label class="custom-control-label" for="checkbox0"
                                            style="color:#C8C8C8;">Remember Me</label>
                                    </div>
                                    <div class="form-group">
                                        <button type="sumbit" class="butonas plinut" name="login">Login</button>
                                        <div style="position: relative;float: right;">
                                            <button onclick="window.location='register.php';" type="button"
                                                class="butonas green" name="register">Register</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php require "includes/footer.inc.php"; //FOOTER ?>
        </div>
    </div>
    </div>

    <!-- JavaScript files-->
    <script src="template\vendor\jquery\jquery.min.js"></script>
    <script src="template\vendor\popper.js\umd\popper.min.js"> </script>
    <script src="template\vendor\bootstrap\js\bootstrap.min.js"></script>
    <!-- Main File-->
    <script src="template\js\front.js"></script>
</body>

</html>