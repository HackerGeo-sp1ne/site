<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php
    $title = "Random Number";
    $page = "r_number";

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
                    <h2 class="no-margin-bottom">Home > <i>Random Number</i></h2>
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
                                                        <h4 style="color:var(--primary-color)">Welcome to the Random Number!</h4>
                                                        <p class="mb-0">You can choose one number between 1 and 12.</p>
                                                        <p class="mb-0"><small>Type how much credits you want to gamble and if you win you will get x4 gambling amount!</small></p><br>

                                                        <h4 style="color:red;">WARNING</h4>
                                                        <p><b>Use Random Number at your own risk. We are not responsible if you lose money or credits on our site, if you decide to gamble you do it at your own risk.</b></p>
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
                                                    <center>
                                                        <h4>Current Balance: <span style="color:green;"><?php echo Utils::balanceFormat($user_data['balance']);?>&euro;</span></h4>
                                                        <div id="result">
                                                            <?php 
                                                                if(isset($_POST["submit_number"]) && ($_POST["amount"]>0)) {
                                                                    $amount = $_POST["amount"];
                                                                    if ($amount<=$user_data['balance']) {
                                                                        $number = $_POST["number"];
                                                                        $random = rand(1,10);
                                                                        echo '<h1>Random number is <span style="color:var(--primary-color);">'.$random.'</span></h1>';
                                                                        echo '<h1>You selected <span style="color:var(--primary-color);">'.$number .'</span></h1>';
                                                                        if ($random == $number) {
                                                                            //win
                                                                            echo "<span style='color:green;'>You win!</span>";
                                                                            $win = ($amount*3);
                                                                            $new_balance = round($user_data['balance']+$win,2);
                                                                            $query = $mysqli->prepare("UPDATE `SF_Users` SET `balance` = ? WHERE `user_id` = ? ");
                                                                            $query -> bind_param('si',$new_balance,$user_data['user_id']);

                                                                            $query -> execute();
                                                                            $query -> close();

                                                                            $utils = new Utils();
                                                                            $utils->add_log(Session::exists("user"),"Wined <span style='font-weight: bold;color:green'>".$win."€</span> at <span style='font-weight: bold;color:var(--primary-color)'>random numbers</span>.");
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
                                                    </center>
                                                </div><br>
                                                <form method="POST">
                                                    <div class="row">
                                                        <label class="col-md-4 app_style">
                                                            <b style="font-weight: bold;">Enter amount you want to gamble:</b>
                                                        </label>
                                                        <div class="col-md-8 app_style">
                                                            <input type="number" min="0.1" name="amount" value="<?php if (isset($_POST['submit_number'])){echo $_POST['amount'];}else{echo '0.1';} ?>" class="form-control" step="0.01">
                                                        </div>
                                                    </div><br>
                                                    <div class="row">
                                                        <label class="col-md-4 app_style">
                                                            <b style="font-weight: bold;">Enter number (1-10):</b>
                                                        </label>
                                                        <div class="col-md-8 app_style">
                                                            <input type="number" min="1" max="10" name="number" value="<?php if (isset($_POST['submit_number'])){echo $_POST['number'];}else{echo '1';} ?>" class="form-control" step="1">
                                                        </div>
                                                    </div>
                                                    <br/>
                                                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                        <button type="submit" name="submit_number"
                                                            class="btn btn-block butonas">Guess The Number</button>
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