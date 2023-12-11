<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php 
        $title = "Register";
        $page = "register";
        require "config.php"; //CONFIG
        require "includes/head.inc.php"; // <HEAD>

        if (Utils::is_user_logged()) {
            header("location: index.php"); exit();
        }
    ?>

<body>
    <div class="page">
        <?php require "includes/navbar.inc.php"; //NAVBAR 

            $register = new User();

            if (isset($_GET['r'])){
                $refferal = new Referral();
                $refferal->exists($_GET['r'],"clicks");
            }

            if (isset($_POST['register']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['captcha_code']) && Session::exists('verify_code') ) {
                if( $_POST['captcha_code'] == $_SESSION['verify_code'] ) {
                    $register->try_register( array(
                        'username' => $_POST['username'], 
                        'email' => $_POST['email'],
                        'password' => $_POST['password'],
                        'password2' => $_POST['password2']
                    ));
                } else {
                    $register->addError('Incorrect code!');
                }
            }
            ?>
        <div class="content-inner">

            <section class="tables">
                <div class="container-fluid">
                    <?php if (!empty($register->errors())) { ?>
                    <div class="col-lg-16">
                        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                            role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <?php 
                                            foreach($register->errors() as $error){  
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
                                    <div class="form-group">
                                        <label class="form-control-label">Username</label>
                                        <input required type="text" placeholder="Username" class="form-control"
                                            value="<?php if (isset($_POST['username'])){ echo $_POST['username'];} ?>"
                                            name="username" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Email</label>
                                        <input required type="email" placeholder="Email" class="form-control"
                                            value="<?php if (isset($_POST['email'])){ echo $_POST['email'];} ?>"
                                            name="email" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Password</label>
                                        <input required type="password" placeholder="Password" class="form-control"
                                            name="password" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Repeat Password</label>
                                        <input required type="password" placeholder="Password" class="form-control"
                                            name="password2" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Verify Code</label></br>
                                        <img style="margin-bottom:10px;" src="includes/captcha.php">
                                        <input required type="number" id="password" placeholder="Input the code"
                                            class="form-control" name="captcha_code" />
                                    </div>

                                    <br />
                                    <div class="form-group">
                                        <button type="sumbit" class="butonas plinut" name="register">Register</button>
                                        <div style="position: relative;float: right;">
                                            <button onclick="window.location='login.php';" type="button"
                                                class="butonas green" name="login">Login</button>
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