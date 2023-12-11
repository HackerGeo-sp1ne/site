<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php
    $title = "Lottery 20/90";
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
                    <h2 class="no-margin-bottom">Home > <i>Lottery 20/90</i></h2>
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
                                                        <p class="mb-0">You need to choose 20 different numbers and press <b>Done</b> to start lottery!</p>
                                                        <p class="mb-0"><small>Type how many credits you want to bet and then choose are you BLACK or RED.</small></p><br>

                                                        <h4>Winnings table</h4>
                                                        <p>
                                                            If you predict 7 numbers, bet * x2<br>
                                                            If you predict 8 numbers, bet * x4<br>
                                                            If you predict 9 numbers, bet * x6<br>
                                                            If you predict 10 numbers, bet * x7<br>
                                                            If you predict 11 numbers, bet * x10<br>
                                                            If you predict 12 numbers, bet * x13<br>
                                                            If you predict 13 numbers, bet * x16<br>
                                                            If you predict 14 numbers, bet * x20<br>
                                                            If you predict 15 numbers, bet * x24<br>
                                                            If you predict 16 numbers, bet * x28<br>
                                                            If you predict 17 numbers, bet * x32<br>
                                                            If you predict 18 numbers, bet * x36<br>
                                                            If you predict 19 numbers, bet * x40<br>
                                                            If you predict 20 numbers, bet * x44
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
                                                                        if (count($numberss)==20) {
                                                                            $random_numbers=[];
                                                                            for ($i = 0; $i < 20; $i++) {
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
                                                                            echo "<span style='color:".($numbersfinded>6 ? "green" : "red").";'>You find $numbersfinded numbers</span>";
                                                                            
                                                                            $oricat=0;
                                                                            if ($numbersfinded==7){
                                                                                $oricat=2;
                                                                            } else if ($numbersfinded==8){
                                                                                $oricat=4;
                                                                            } else if ($numbersfinded==9){
                                                                                $oricat=6;
                                                                            } else if ($numbersfinded==10){
                                                                                $oricat=7;
                                                                            } else if ($numbersfinded==11){
                                                                                $oricat=10;
                                                                            } else if ($numbersfinded==12){
                                                                                $oricat=13;
                                                                            } else if ($numbersfinded==13){
                                                                                $oricat=16;
                                                                            } else if ($numbersfinded==14){
                                                                                $oricat=20;
                                                                            } else if ($numbersfinded==15){
                                                                                $oricat=24;
                                                                            } else if ($numbersfinded==16){
                                                                                $oricat=28;
                                                                            } else if ($numbersfinded==17){
                                                                                $oricat=32;
                                                                            } else if ($numbersfinded==18){
                                                                                $oricat=36;
                                                                            } else if ($numbersfinded==19){
                                                                                $oricat=40;
                                                                            } else if ($numbersfinded==20){
                                                                                $oricat=44;
                                                                            }

                                                                            $winprice=($amount*$oricat);
                                                                            if ($oricat>1) {
                                                                                //win
                                                                                echo "<br><span style='color:green;'>You win $winprice&euro;!</span>";
                                                                                $new_balance = round($user_data['balance']+$winprice,2);
                                                                                $query = $mysqli->prepare("UPDATE `SF_Users` SET `balance` = ? WHERE `user_id` = ? ");
                                                                                $query -> bind_param('si',$new_balance,$user_data['user_id']);
                                                                                $query -> execute();
                                                                                $query -> close();

                                                                                $utils = new Utils();
                                                                                $utils->add_log(Session::exists("user"),"Wined <span style='font-weight: bold;color:green'>".$winprice."€</span> at <span style='font-weight: bold;color:var(--primary-color)'>lottery 20/90</span>.");
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
                                                                            echo "<span style='color:red;'>Please select 20 numbers!</span>";
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
                                                                if (countSelected < 20 && (myNumbers.includes(number) == false)) {
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
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="41" id="lot-41">41</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="42" id="lot-42">42</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="43" id="lot-43">43</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="44" id="lot-44">44</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="45" id="lot-45">45</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="46" id="lot-46">46</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="47" id="lot-47">47</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="48" id="lot-48">48</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="49" id="lot-49">49</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="50" id="lot-50">50</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="51" id="lot-51">51</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="52" id="lot-52">52</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="53" id="lot-53">53</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="54" id="lot-54">54</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="55" id="lot-55">55</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="56" id="lot-56">56</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="57" id="lot-57">57</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="58" id="lot-58">58</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="59" id="lot-59">59</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="60" id="lot-60">60</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="61" id="lot-61">61</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="62" id="lot-62">62</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="63" id="lot-63">63</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="64" id="lot-64">64</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="65" id="lot-65">65</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="66" id="lot-66">66</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="67" id="lot-67">67</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="68" id="lot-68">68</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="69" id="lot-69">69</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="70" id="lot-70">70</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="71" id="lot-71">71</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="72" id="lot-72">72</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="73" id="lot-73">73</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="74" id="lot-74">74</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="75" id="lot-75">75</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="76" id="lot-76">76</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="77" id="lot-77">77</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="78" id="lot-78">78</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="79" id="lot-79">79</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="80" id="lot-80">80</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="81" id="lot-81">81</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="82" id="lot-82">82</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="83" id="lot-83">83</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="84" id="lot-84">84</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="85" id="lot-85">85</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="86" id="lot-86">86</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="87" id="lot-87">87</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="88" id="lot-88">88</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="89" id="lot-89">89</div>
                                                                        </td>
                                                                        <td style="width: 30px; height: 30px; text-align: center;">
                                                                            <div onclick="selectNumber(event)" data-number="90" id="lot-90">90</div>
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