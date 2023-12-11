<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php
    $title = "Lottery 7/40";
    $page = "lottery";
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
                    <h2 class="no-margin-bottom">Home > <i>Lottery 7/40</i></h2>
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
                                                        <h4 style="color:var(--primary-color)">Welcome to the Lottery!</h4>
                                                        <p class="mb-0">You need to choose 7 different numbers and press <b>Done</b> to start lottery!</p>
                                                        <p class="mb-0"><small>Type how many credits you want to bet and then choose are you BLACK or RED.</small></p><br>

                                                        <h4>Winnings table</h4>
                                                        <p>
                                                            If you predict 3 numbers, bet * x4<br>
                                                            If you predict 4 numbers, bet * x7<br>
                                                            If you predict 5 numbers, bet * x14<br>
                                                            If you predict 6 numbers, bet * x20<br>
                                                            If you predict 7 numbers, bet * x40
                                                        </p><br>
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
                                                    <center>
                                                        <h4>Current Balance: <span style="color:green;"><?php echo Utils::balanceFormat($user_data['balance']);?>&euro;</span></h4>
                                                        <div id="result">
                                                            <?php 
                                                                if(isset($_POST["submit_lottery"]) && $_POST["submit_lottery"] && ($_POST["amount"]>0)) {
                                                                    $amount = $_POST["amount"];
                                                                    if ($amount<=$user_data['balance']) {
                                                                        $numbers = substr($_POST["lotonumbers"], 0, -1);
                                                                        $numberss = explode(',', $numbers);
                                                                        $numbersfinded=0;
                                                                        if (count($numberss)==7) {
                                                                            $random_numbers=[];
                                                                            for ($i = 0; $i < 7; $i++) {
                                                                                $random_number=rand(1,40);
                                                                                array_push($random_numbers,$random_number);
                                                                                if (in_array($random_number, $numberss)) $numbersfinded++;
                                                                            }

                                                                            $stringrandomgasite="";
                                                                            foreach ($random_numbers as $value) {
                                                                                $stringrandomgasite=$stringrandomgasite.$value.",";
                                                                            }

                                                                            echo "<h1>Random numbers <span style='color:var(--primary-color);'>".substr($stringrandomgasite, 0, -1)."</span></h1>";
                                                                            echo "<h1>You selected <span style='color:var(--primary-color);'>".$numbers."</span></h1>";
                                                                            echo "<span style='color:".($numbersfinded>2 ? "green" : "red").";'>You find $numbersfinded numbers</span>";
                                                                            
                                                                            $oricat=0;
                                                                            if ($numbersfinded==3){
                                                                                $oricat=4;
                                                                            } else if ($numbersfinded==4){
                                                                                $oricat=7;
                                                                            } else if ($numbersfinded==5){
                                                                                $oricat=14;
                                                                            } else if ($numbersfinded==6){
                                                                                $oricat=20;
                                                                            } else if ($numbersfinded==7){
                                                                                $oricat=40;
                                                                            } else if ($numbersfinded==4){
                                                                                $oricat=7;
                                                                            }

                                                                            $winprice=($amount*$oricat);
                                                                            if ($oricat>3) {
                                                                                //win
                                                                                echo "<br><span style='color:green;'>You win $winprice&euro;!</span>";

                                                                                $new_balance = round($user_data['balance']+$winprice,2);
                                                                                $query = $mysqli->prepare("UPDATE `SF_Users` SET `balance` = ? WHERE `user_id` = ? ");
                                                                                $query -> bind_param('si',$new_balance,$user_data['user_id']);

                                                                                $query -> execute();
                                                                                $query -> close();

                                                                                $utils = new Utils();
                                                                                $utils->add_log(Session::exists("user"),"Wined <span style='font-weight: bold;color:green'>".$winprice."€</span> at <span style='font-weight: bold;color:var(--primary-color)'>lottery 7/49</span>.");
                                                                            } else {
                                                                                //lose
                                                                                echo "<br><span style='color:red;'>You lose!</span>";
                                                                                $new_balance = round($user_data['balance']-$amount,2);
                                                                                $query = $mysqli->prepare("UPDATE `SF_Users` SET `balance` = ? WHERE `user_id` = ? ");
                                                                                $query -> bind_param('si',$new_balance,$user_data['user_id']);

                                                                                $query -> execute();
                                                                                $query -> close();
                                                                            }

                                                                        } else {
                                                                            echo "<span style='color:red;'>Please select 7 numbers!</span>";
                                                                        }
                                                                    } else {
                                                                        echo "<span style='color:red;'>Dont have money!</span>";
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                        <script>
                                                            let countSelected = 0;
                                                            let myNumbers = []
                                                            function selectNumber(event) {
                                                                let number = event.target.getAttribute("data-number");
                                                                if (countSelected < 7 && (myNumbers.includes(number) == false)) {
                                                                    myNumbers.push(number);
                                                                    event.target.style.background = 'var(--primary-color)';
                                                                    countSelected++;
                                                                    var currentNumbers = document.getElementById("lotonumbers").value;
                                                                    document.getElementById("lotonumbers").value = currentNumbers + number + ","
                                                                }
                                                            }
                                                        </script>
                                                        <div id="lottery" class="col-sm-6">
                                                            <table border="1" style="width: 100%;border: 1px solid var(--primary-color);">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="1" id="lot-1">1</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="2" id="lot-2">2</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="3" id="lot-3">3</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="4" id="lot-4">4</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="5" id="lot-5">5</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="6" id="lot-6">6</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="7" id="lot-7">7</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="8" id="lot-8">8</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="9" id="lot-9">9</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="10" id="lot-10">10</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="11" id="lot-11">11</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="12" id="lot-12">12</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="13" id="lot-13">13</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="14" id="lot-14">14</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="15" id="lot-15">15</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="16" id="lot-16">16</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="17" id="lot-17">17</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="18" id="lot-18">18</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="19" id="lot-19">19</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="20" id="lot-20">20</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="21" id="lot-21">21</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="22" id="lot-22">22</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="23" id="lot-23">23</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="24" id="lot-24">24</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="25" id="lot-25">25</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="26" id="lot-26">26</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="27" id="lot-27">27</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="28" id="lot-28">28</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="29" id="lot-29">29</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="30" id="lot-30">30</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="31" id="lot-31">31</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="32" id="lot-32">32</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="33" id="lot-33">33</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="34" id="lot-34">34</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="35" id="lot-35">35</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="36" id="lot-36">36</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="37" id="lot-37">37</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="38" id="lot-38">38</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="39" id="lot-39">39</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="40" id="lot-40">40</div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </center>
                                                </div><br>
                                                <form method="POST">
                                                    <input type="hidden" id="lotonumbers" name="lotonumbers" value="">
                                                    <div class="row">
                                                        <label class="col-md-4 app_style">
                                                            <b style="font-weight: bold;">Enter amount you want to gamble:</b>
                                                        </label>
                                                        <div class="col-md-8 app_style">
                                                            <input type="number" min="0.1" name="amount" value="<?php if (isset($_POST['submit_lottery'])){echo $_POST['amount'];}else{echo '0.1';} ?>" class="form-control" step="0.01">
                                                        </div>
                                                    </div><br>
                                                    
                                                    <div class="text-center row">
                                                        <div class="col-md-12"><input type="submit" class="btn btn-primary" value="SUBMIT" name="submit_lottery">&nbsp;<a href="lottery.php" class="btn btn-danger">RESET</a></div>
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