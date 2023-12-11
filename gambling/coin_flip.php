<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php
    $title = "Coin Flip";
    $page = "c_flip";
    $use_directory = "yesss"; // de modificat pe viitor

    require "../config.php"; //CONFIG
    require "../includes/head.inc.php"; // <HEAD>

    if (!Utils::is_user_logged()) {
        header("location: ../login.php"); exit();
    }
?>

<body>
    <div class="page">
        <?php require "../includes/navbar.inc.php"; //NAVBAR ?>
        <div class="content-inner">
            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">Home > <i>Coin Flip</i></h2>
                </div>
            </header>
            <section class="tables">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body" style="border-radius:10px;border:2px solid red;">
                                                <div class="col-sm-12 p-md-0">
                                                    <div class="welcome-text">
                                                        <h4 style="color:var(--primary-color)">Welcome to the Coin Flip!</h4>
                                                        <p class="mb-0">Here you can bet your credits and either win or loose, chances are 50/50.</p>
                                                        <p class="mb-0"><small>Type how many credits you want to bet and then choose are you BLACK or RED.</small></p><br>

                                                        <h4 style="color:red;">WARNING</h4>
                                                        <p><b>Use Coin Flip at your own risk. We are not responsible if you lose money or credits on our site, if you decide to gamble you do it at your own risk.</b></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body" style="border-radius:10px;border:2px solid var(--primary-tr);">
                                                <div class="col-sm-12">
                                                    <style>
                                                        #coin {
                                                            position: relative;
                                                            margin: 0 auto;
                                                            width: 100px;
                                                            height: 100px;
                                                            cursor: pointer;
                                                        }
                                                        #coin div {
                                                            width: 100%;
                                                            height: 100%;
                                                            border: 1px solid white;
                                                            -webkit-border-radius: 50%;
                                                            -moz-border-radius: 50%;
                                                            border-radius: 50%;
                                                            -webkit-box-shadow: inset 0 0 45px rgba(255, 255, 255, 0.3),
                                                                0 12px 20px -10px rgba(0, 0, 0, 0.4);
                                                            -moz-box-shadow: inset 0 0 45px rgba(255, 255, 255, 0.3),
                                                                0 12px 20px -10px rgba(0, 0, 0, 0.4);
                                                            box-shadow: inset 0 0 45px rgba(255, 255, 255, 0.3),
                                                                0 12px 20px -10px rgba(0, 0, 0, 0.4);
                                                        }
                                                        .side-a {
                                                            background-color: #bb0000;
                                                        }
                                                        .side-b {
                                                            background-color: #3e3e3e;
                                                        }

                                                        #coin {
                                                            transition: -webkit-transform 1s ease-in;
                                                            -webkit-transform-style: preserve-3d;
                                                        }
                                                        #coin div {
                                                            position: absolute;
                                                            -webkit-backface-visibility: hidden;
                                                        }
                                                        .side-a {
                                                            z-index: 100;
                                                        }
                                                        .side-b {
                                                            -webkit-transform: rotateY(-180deg);
                                                        }

                                                        #coin.heads {
                                                            -webkit-animation: flipHeads 3s ease-out forwards;
                                                            -moz-animation: flipHeads 3s ease-out forwards;
                                                            -o-animation: flipHeads 3s ease-out forwards;
                                                            animation: flipHeads 3s ease-out forwards;
                                                        }
                                                        #coin.tails {
                                                            -webkit-animation: flipTails 3s ease-out forwards;
                                                            -moz-animation: flipTails 3s ease-out forwards;
                                                            -o-animation: flipTails 3s ease-out forwards;
                                                            animation: flipTails 3s ease-out forwards;
                                                        }

                                                        @-webkit-keyframes flipHeads {
                                                            from {
                                                                -webkit-transform: rotateY(0);
                                                                -moz-transform: rotateY(0);
                                                                transform: rotateY(0);
                                                            }
                                                            to {
                                                                -webkit-transform: rotateY(1800deg);
                                                                -moz-transform: rotateY(1800deg);
                                                                transform: rotateY(1800deg);
                                                            }
                                                        }
                                                        @-webkit-keyframes flipTails {
                                                            from {
                                                                -webkit-transform: rotateY(0);
                                                                -moz-transform: rotateY(0);
                                                                transform: rotateY(0);
                                                            }
                                                            to {
                                                                -webkit-transform: rotateY(1980deg);
                                                                -moz-transform: rotateY(1980deg);
                                                                transform: rotateY(1980deg);
                                                            }
                                                        }
                                                    </style>
                                                    <center>
                                                        <h4>Current Balance: <span style="color:green;"><?php echo Utils::balanceFormat($user_data['balance']);?>&euro;</span></h4>
                                                        <div id="result">
                                                            <?php 

                                                                if(isset($_POST["submit_coin"]) && ($_POST["amount"]>0)) {
                                                                    $amount = $_POST["amount"];
                                                                    if ($amount<=$user_data['balance']) {

                                                                        $coin = 1;
                                                                        if ($_POST["submit_coin"] == 'black') {
                                                                            $coin = 0;
                                                                        }
                                                                        $random = rand(0,1);

                                                                        echo '<script>jQuery(document).ready(function ($) {
                                                                            $("#coin").removeClass();
                                                                            setTimeout(function () {
                                                                              '.($random ? '$("#coin").addClass("heads");' : '$("#coin").addClass("tails");').'
                                                                            }, 100);
                                                                          });
                                                                        </script>';
                                                                        
                                                                        echo '<h1>Coin flip is <span style="color:var(--primary-color);">'.($random ? "red" : "black") .'</span></h1>';
                                                                        echo '<h1>You selected <span style="color:var(--primary-color);">'.($coin ? "red" : "black") .'</span></h1>';
                                                                       
                                                                        if ($random == $coin) {
                                                                            //win
                                                                            echo "<span style='color:green;'>You win!</span>";
                                                                            $new_balance = round($user_data['balance']+$amount,2);
                                                                            $query = $mysqli->prepare("UPDATE `SF_Users` SET `balance` = ? WHERE `user_id` = ? ");
                                                                            $query -> bind_param('si',$new_balance,$user_data['user_id']);
                                                                            $query -> execute();
                                                                            $query -> close();

                                                                            $utils = new Utils();
                                                                            $utils->add_log(Session::exists("user"),"Wined <span style='font-weight: bold;color:green'>".$amount."€</span> at <span style='font-weight: bold;color:var(--primary-color)'>coin flip</span>.");
                                                                        } else {
                                                                            //lose
                                                                            echo "<span style='color:red;'>You lose!</span>";
                                                                            $new_balance = round($user_data['balance']-$amount,2);
                                                                            $query = $mysqli->prepare("UPDATE `SF_Users` SET `balance` = ? WHERE `user_id` = ? ");
                                                                            $query -> bind_param('si',$new_balance,$user_data['user_id']);
                                                                            $query -> execute();
                                                                            $query -> close();
                                                                        }
                                                                    } else {
                                                                        echo "<span style='color:red;'>Dont have money!</span>";
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                        <div id="coin">
                                                            <div class="side-a"></div>
                                                            <div class="side-b"></div>
                                                        </div>
                                                    </center>
                                                </div><br>
                                                <form method="POST">
                                                    <div class="row">
                                                        <label class="col-md-4 app_style">
                                                            <b style="font-weight: bold;">Enter amount you want to gamble:</b>
                                                        </label>
                                                        <div class="col-md-8 app_style">
                                                            <input type="number" min="0.1" name="amount" value="<?php if (isset($_POST['submit_coin'])){echo $_POST['amount'];}else{echo '0.1';} ?>" class="form-control" step="0.01">
                                                        </div>
                                                    </div><br>
                                                    <br/>
                                                    <div class="text-center row">
                                                        <div class="col-md-12"><input type="submit" class="btn btn-hudcolor" name="submit_coin" value="red">&nbsp;<input type="submit" class="btn btn-secondary" name="submit_coin" value="black"></div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->
                                    <!-- Column -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php require "../includes/footer.inc.php"; //FOOTER ?>
        </div>
    </div>
    </div>

    <!-- JavaScript files-->
    <script src="..\template\vendor\jquery\jquery.min.js"></script>
    <script src="..\template\vendor\popper.js\umd\popper.min.js"> </script>
    <script src="..\template\vendor\bootstrap\js\bootstrap.min.js"></script>
    <!-- Main File-->
    <script src="..\template\js\front.js"></script>
</body>

</html>