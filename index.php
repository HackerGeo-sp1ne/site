<!doctype html>

<html id="color-picker" data-theme="default" lang="en-US">

<?php


// 404 daca e banat pe ip etc
        $title = "Main";
        $page = "index";
        require "config.php"; //CONFIG
        require "includes/head.inc.php"; // <HEAD>

        spl_autoload_register(function ($class_name) {
            require "includes/classes/".$class_name.".php";
        });

        if (isset($_GET['verify_email'])) {
            $userr = new User();
            $userr->verify_email($_GET['verify_email']);
        }
    ?>

<body>

    <div id="add_announce" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal fade show" id="large-Modal" tabindex="-1" role="dialog"
            style="z-index: 1050; display: block; padding-right: 20px;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Global Announce</h3>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    <div class="col-sm-12">
                                        <form method="POST">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <label class="col-md-3 app_style">
                                                        <b style="font-weight: bold;">Announce Description</b>
                                                    </label>
                                                    <div class="col-md-9 app_style">
                                                        <textarea required placeholder="Please type an description"
                                                            class="form-control" name="ann_desc"></textarea>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="d-flex flex-sm-row flex-column justify-content-end">
                                                    <input type="submit" name="adauga_anunt" value="Submit"
                                                        style="margin-top:10px;"
                                                        class="btn btn-success btn-block glow mr-sm-1 mb-1">
                                                </div>
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

    <div class="page">
        <?php require "includes/navbar.inc.php"; //NAVBAR

                if (Utils::is_user_logged() && Utils::is_user_permission($user_groups['groups'],"administration.announce") && isset($_POST['delete_announce']) && isset($_POST['announce_to_del'])) {
                  $id=$_POST['announce_to_del'];
                  $result = $mysqli->prepare("SELECT * FROM `SF_Announces` WHERE `id` = ?");
                  $result -> bind_param('i',$id);
                  $result -> execute();
                  $rez = $result -> get_result();
                  $row = $rez->fetch_assoc();
                  if ($row) {
                    $mysqli->query("DELETE FROM `SF_Announces` WHERE `id` = '".$row['id']."'");
                  }
                  $result->close();
                }

                if (Utils::is_user_logged() && Utils::is_user_permission($user_groups['groups'],"administration.announce") && isset($_POST['adauga_anunt']) && isset($_POST['ann_desc'])) {
                    $desc=$_POST['ann_desc'];
                    $date = date("d-m-Y H:i:s");
                    $query = $mysqli->prepare("INSERT IGNORE INTO SF_Announces(`author`, `posted`, `desc`) VALUES (?,?,?)");
                    $query -> bind_param('sss',Session::exists('user'),$date,$desc);
                    if ($query -> execute()) header('location:');
                    $query -> close();
                }
                $table = $mysqli->query("SELECT * FROM `SF_Users`");
                $row_cnt = $table->num_rows;
            ?>
        <div class="content-inner">
            <!-- Page Header-->
            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">Home > <i>Dashboard</i></h2>
                </div>
            </header>

            <!-- Dashboard Counts Section-->
            <section class="dashboard-counts no-padding-bottom">
                <!-- -->
                <!-- -->


                <div class="container-fluid">
                    <div class="col-lg-14">
                        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                            role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            Site in working...
                        </div>
                    </div>

                    <div class="row bg-white has-shadow">
                        <div class="col-xl-3 col-sm-6">
                            <div class="item d-flex align-items-center">
                                <div class="icon bg-violet"><i class="fa fa-users"></i></div>
                                <div class="title">
                                    <span>
                                        Total<br />
                                        Accounts
                                    </span>
                                    <div class="progress">
                                        <div role="progressbar" style="width: <?php echo $row_cnt."%";?>; height: 4px;"
                                            aria-valuenow="<?php echo $row_cnt;?>" aria-valuemin="0" aria-valuemax="100"
                                            class="progress-bar bg-violet"></div>
                                    </div>
                                </div>
                                <div class="number"><strong><?php echo $row_cnt;?></strong></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="item d-flex align-items-center">
                                <div class="icon bg-red"><i class="fa fa-tasks"></i></div>
                                <?php
                                            $orders_cnt = $mysqli->query("SELECT * FROM `SF_Scripts` WHERE `sells` > 0");
                                            $total_orders = 0;
                                            while ($row = $orders_cnt->fetch_assoc()) {
                                                $total_orders=$total_orders+$row['sells'];
                                            }
                                            $pg_orders = $total_orders * 100 / 69;
                                        ?>
                                <div class="title">
                                    <span>
                                        Total<br />
                                        Orders
                                    </span>
                                    <div class="progress">
                                        <div role="progressbar"
                                            style="width: <?php echo $pg_orders."%";?>; height: 4px;"
                                            aria-valuenow="<?php echo $pg_orders;?>" aria-valuemin="0"
                                            aria-valuemax="100" class="progress-bar bg-red"></div>
                                    </div>
                                </div>
                                <div class="number"><strong><?php echo $total_orders;?></strong></div>
                            </div>
                        </div>
                        <!-- Item -->
                        <div class="col-xl-3 col-sm-6">
                            <div class="item d-flex align-items-center">
                                <div class="icon bg-orange"><i class="fa fa-money"></i></div>
                                <?php
                                            $clients_tbl = $mysqli->query("SELECT * FROM `SF_Users` WHERE `client` = 'Yes'")->num_rows;
                                            $clients_total = $clients_tbl * 100 / 10;
                                        ?>
                                <div class="title">
                                    <span>
                                        Total<br />
                                        Clients
                                    </span>
                                    <div class="progress">
                                        <div role="progressbar"
                                            style="width: <?php echo $clients_total."%";?>; height: 4px;"
                                            aria-valuenow="<?php echo $clients_total;?>" aria-valuemin="0"
                                            aria-valuemax="100" class="progress-bar bg-orange"></div>
                                    </div>
                                </div>
                                <div class="number"><strong><?php echo $clients_tbl;?></strong></div>
                            </div>
                        </div>


                        <!-- clained php script-->
                        <?php
                            if (Utils::is_user_logged()) {
                                $noww = new DateTime;
                                $agoo = new DateTime($user_data['last_clained']);

                                if ( ((array) $noww->diff( $agoo ))['d'] >= 1 ) {
                                    $acces_clain = true;
                                    $acces_text = "Clain now";
                                } else {
                                    $acces_clain = false;
                                    $acces_text = "Already claimed";
                                }
                                if (isset($_POST["clain_form"])) {
                                    if ($acces_clain) {
                                        $date = date("d-m-Y H:i");
                                        $winnnn = rand(1,2);
                                        $query = $mysqli->prepare("UPDATE `SF_Users` SET `last_clained` = ?, `balance` = `balance` + ? WHERE `user_id` = ? ");
                                        $query -> bind_param('ssi', $date, $winnnn,$user_data['user_id']);
                                        $query -> execute();
                                        $query -> close();

                                        echo '<META HTTP-EQUIV="Refresh" Content="0; URL="index.php">';

                                    }
                                }
                            } else {
                                $acces_clain = false;
                                $acces_text = "Please sign in";
                            }
                        ?>
                        <!-- clained php script-->

                        <!-- Item -->
                        <div class="col-xl-3 col-sm-6">
                            <div class="item d-flex align-items-center">
                                <div class="title">
                                    <span>
                                        Clain FREE Credits</br>
                                        <div class="col-12">
                                            <form method="post" >
                                                <input type="submit" name="clain_form" value="<?php echo $acces_text; ?>" class="butonas rosu" style="border-width: 1px;" <?php echo ($acces_clain ? "" : "disabled"); ?>>
                                            </form>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="tables">
                <div class="container-fluid">

                    <div class="col-lg-14"
                        style="border:2px solid var(--primary-color);background-color:#31333A;border-radius:10px;margin-bottom:10px;margin-top:-20px;border:1px solid var(--primary-color);box-shadow:inset 0 0 15px var(--primary-tr);">
                        <div class="has-shadow">
                            <style>
                            .wrapper {
                                margin: 10px 10px;
                                overflow: hidden;
                            }

                            .photobanner {
                                position: relative;
                                display: flex;
                                width: 100%;
                            }

                            .photobanner {
                                animation: bannermove 20s linear infinite reverse;
                            }

                            @keyframes bannermove {
                                from {
                                    left: 500px;
                                }

                                to {
                                    left: -2000px;
                                }
                            }
                            </style>
                            <div class="wrapper">
                                <div class="photobanner">
                                    &nbsp;<a href="https://www.cloud-center.ro/aff.php?aff=393"><img
                                            onContextMenu="return false;"
                                            src="https://www.cloud-center.ro/resurse/banner-3.png"
                                            alt="Host MC, Gazduire MTA, Gazduire CS, Gazduire CSGO, Hosting, Cloud-Center.ro" /></a>
                                    &nbsp;<a href="https://www.cloud-center.ro/aff.php?aff=393"><img
                                            onContextMenu="return false;"
                                            src="https://www.cloud-center.ro/resurse/banner-1.png"
                                            alt="Host MC, Gazduire MTA, Gazduire CS, Gazduire CSGO, Hosting, Cloud-Center.ro" /></a>
                                    &nbsp;<a href="https://www.cloud-center.ro/aff.php?aff=393"><img
                                            onContextMenu="return false;"
                                            src="https://www.cloud-center.ro/resurse/banner-4.png"
                                            alt="Host MC, Gazduire MTA, Gazduire CS, Gazduire CSGO, Hosting, Cloud-Center.ro" /></a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <br>

                    <div class="row">
                        
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="h4"><i class="fa fa-history"></i>&nbsp; BIG NEWS</h3>
                                </div>
                                <div class="card-body">
                                    <h2>Welcome to new <span style="color:var(--primary-color);">STAYFROSTY SHOP</span>!</h2>
                                    <p>If you are new user, you can claim <strong><span style="color: #008000;">FREE</span></strong> <strong><span style="color: #ff0000;">0.05</span></strong> credits, just go to <strong>Dashboard</strong> and on TOP right you will see box where you can claim <strong><span style="color: #ff0000;">FREE Credits</span></strong>.
                                    <br>Also you can play daily <span style="color: #ff00ff;">Spin &amp; Win</span> for Free. You can find Spin &amp; Win in <span style="color: #ff00ff;">Gambling</span> category on left side of page.
                                    <br>To <span style="color: #ff0000;"><strong>purchase</strong></span> products, you need to <strong><span style="color: #ff0000;">TOP UP</span></strong> balance to get credits
                                    <br>There is a <strong><span style="color: #ff0000;">special offer</span></strong> right now on limited time!
                                    <br><br>TOP UP <span style="color: #99cc00;">5€</span> and get <span style="color: #99cc00;">6€</span>
                                    <br>TOP UP <span style="color: #99cc00;">10€</span> and get <span style="color: #99cc00;">12€</span>
                                    <br>TOP UP <span style="color: #99cc00;">20€</span> and get <span style="color: #99cc00;">24€</span>
                                    <br>To top up balance click here - <a style="color: #ff0000;" href="#buy_balance" data-toggle="modal" data-target="#buy_balance">Top UP Balance</a>
                                </div>
                            </div>
                            
                            <div class="daily-feeds card">

                                <div class="card-header" style="background-color:var(--primary-color);">
                                    <h3 class="h4"><i class="fa fa-newspaper-o" style="color:white"></i> News
                                        <?php if (Utils::is_user_logged() && Utils::is_user_permission($user_groups['groups'],"administration.announce")) { ?>
                                            <button data-toggle="modal" data-target="#add_announce" class="btn btn-success btn-sm float-right">New announce</button>
                                        <?php } ?>
                                    </h3>
                                </div>
                                <div class="card-body no-padding">

                                    <?php
                                            $updates_file = file_get_contents("./template/updates.json");
                                            if ($updates_file) {
                                                $file_data = json_decode($updates_file, true);
                                                foreach($file_data['updates'] as $update) {
                                        ?>
                                    <div class="item" id="update">
                                        <div class="feed d-flex justify-content-between">
                                            <div class="feed-body d-flex justify-content-between">
                                                <div class="content">
                                                    <h5><a
                                                            href="#update_<?php echo strtotime($update['posted'])?>"><?php echo $update['title']?></a>
                                                    </h5>
                                                    <span><a href="#upd_<?php echo strtotime($update['posted'])?>"
                                                            data-toggle="collapse" aria-expanded="false">Click here for
                                                            details...</a></span>
                                                </div>
                                            </div>
                                            <div class="date text-right">
                                                <small data-trigger="hover"><i class="fa fa-clock-o"></i>
                                                    <?php echo Utils::time_elapsed_string('@'.strtotime($update['posted']));?></small>
                                            </div>
                                        </div>

                                        <ul aria-expanded="true" id="upd_<?php echo strtotime($update['posted'])?>"
                                            class="collapse"
                                            style="border:1px solid var(--primary-tr);background-color:#23272b;">
                                            <span style="margin: 15px 0;">
                                                <dl>
                                                    <?php foreach($update['updated'] as $update_data) {
                                                                        echo $update_data['id'];?>
                                                    <?php }?>
                                                </dl>
                                                Update by <a href="profile.php?user=<?php echo $update['author']?>"
                                                    class="external"><b
                                                        style="color:var(--primary-color);"><?php echo $update['author']?></b></a>.
                                            </span>
                                        </ul>
                                    </div>
                                    <?php
                                                }
                                            } ?>

                                    <?php
                                        $sql = "SELECT * FROM `SF_Announces` ORDER BY `id` DESC LIMIT 3";
                                        $r_query = $mysqli->query($sql);
                                        while ($row = $r_query->fetch_assoc()) {
                                            echo '<div class="item">
                                                            <div class="feed d-flex justify-content-between">
                                                                <div class="feed-body d-flex justify-content-between">
                                                                    <div class="content">
                                                                        <h5><a href="profile.php?user=' . urlencode($row['author']) . '">' . $row['author'] . '</a></h5>
                                                                        <span>' . $row['desc'] . '</span>
                                                                    </div>
                                                                </div>
                                                            <div class="date text-right">
                                                                <small data-trigger="hover"><i class="fa fa-clock-o"></i> ' . Utils::time_elapsed_string('@' . strtotime($row['posted'])) . ' </small>    ';

                                            if (Utils::is_user_logged() && Utils::is_user_permission($user_groups['groups'], "administration.announce")) {
                                                echo '<form id="delete_a" method="POST">
                                                          <input type="hidden" name="announce_to_del" value="' . $row['id'] . '">
                                                          <button type="submit" name="delete_announce" title="Delete announce" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-xs fa-trash"></i></button>
                                                        </form>';
                                            }
                                            echo '</div>
                                                        </div>
                                                      </div>';
                                        }
                                        $r_query->close();


                                            ?>
                                    <script>
                                    document.querySelector("#delete_a").addEventListener('click', function(e) {
                                        if (!confirm("You are sure?")) {
                                            e.preventDefault();
                                        }
                                    })
                                    </script>
                                </div>
                            </div>

                            <div class="card">
                                <h3 class="card-header b-b bg-default "><i class="fa fa-comments"></i>&nbsp; Chat</h3>
                                <div class="card-body ">
                                    <?php
                                                if (Utils::is_user_logged() && isset($_POST['SendMessage'])) {
                                                    if (!empty($_POST["msg"])) {
                                                        $date = date("d-m-Y H:i:s");
                                                        $param_msg = $_POST["msg"];
                                                        $id = $_SESSION["id"];
                                                        $query = $mysqli->prepare("INSERT IGNORE INTO SF_Chats(`author_id`, `text`, `posted`) VALUES(?,?,?)");
                                                        $query -> bind_param('iss',$id,$param_msg,$date);
                                                        $query -> execute();
                                                        $query -> close();
                                                    }
                                                }
                                            ?>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <script>
                                                function updateScroll() {
                                                    var element = document.getElementById("chat");
                                                    element.scrollTop = element.scrollHeight;
                                                }

                                                function getMessages() {
                                                    $("#chat").load("includes/classes/Chat.php");
                                                }

                                                setInterval(updateScroll, 2000);
                                                setInterval(getMessages, 1000);
                                            </script>
                                            <style>
                                                input[type="text"]:disabled {
                                                    background-color: #272931;
                                                    cursor: not-allowed;
                                                }
                                                input[type="submit"]:disabled {
                                                    cursor: not-allowed;
                                                }
                                            </style>
                                            <ul class="list-unstyled modal-content-background scrolut" id="chat">
                                            </ul>
                                        </div>

                                        <form method="POST">
                                            <div class="row chatbox-input pt-4"
                                                style="margin-top:-30px;margin-bottom:-20px;">
                                                <div class="col-lg-14 col-md-10 col-sm-14">
                                                    <div class="form-group">
                                                        <input type="text" name="msg" class="form-control contrast"
                                                            maxlength="70"
                                                            <?php if(Utils::is_user_logged()) {echo "placeholder='Whats on your mind?'";} else {echo "placeholder='Please login' disabled";} ?>
                                                            required="" />
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <input type="submit" class="nav-link logout butonas plinut"
                                                        style="padding: 5px 10px;" value="Send" name="SendMessage"
                                                        <?php if( !Utils::is_user_logged() ) {echo 'disabled';}?> />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="daily-feeds card">
                                <div class="card-header">
                                    <h3 class="h4"><i class="fa fa-history"></i>&nbsp; Last 7 logs</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="row">#</th>
                                                    <th>User</th>
                                                    <th>Log</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <?php
                                                        $sql = "SELECT * FROM `SF_Logs` ORDER BY `id` desc limit 7";
                                                        $r_query = $mysqli->query($sql);
                                                        $id_logs = 0;
                                                        while ($row = $r_query->fetch_assoc()) {
                                                            $id_logs++;
                                                            echo '<tbody>
                                                                    <tr>
                                                                        <td>'.$id_logs.'</td>
                                                                        <td><a href="profile.php?user='.urlencode($row['author']).'">'.$row["author"].'</a></td>
                                                                        <td>'.$row["log"].'</td>
                                                                        <td data-placement="bottom" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="'.$row['posted'].'"><i class="fa fa-clock-o"></i> '.Utils::time_elapsed_string('@'.strtotime($row['posted'])).'</td>
                                                                    </tr>
                                                                </tbody>';
                                                        }
                                                        $r_query->close();
                                                    ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">

                            <div class="daily-feeds card">
                                <div class="card-body">
                                    <center>
                                        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4106199750890370"
                                            crossorigin="anonymous"></script>
                                        <!-- RECLAME -->
                                        <ins class="adsbygoogle"
                                            style="display:block"
                                            data-ad-client="ca-pub-4106199750890370"
                                            data-ad-slot="9827463299"
                                            data-ad-format="auto"
                                            data-full-width-responsive="true"></ins>
                                        <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script>
                                    </center>

                                    <!--
                                             <br>
                                            <div>
                                                <div style="display:inline;" id="discord-button">
                                                    <a href="https://discord.gg/84GWD9H" style="background-color:var(--primary-tr);padding: 4px 15px;" target="_blank">
                                                        <div style="font-size:25px; margin-right: 15px">
                                                            <i class="fa fa-sign-out"></i>
                                                        </div>
                                                        <span style="color:white">Direct Connect</span>
                                                    </a>
                                                </div>

                                                <div style="display:inline;" id="discord-button">
                                                    <a href="https://discord.gg/84GWD9H" target="_blank">
                                                        <div class="icon">
                                                            <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 245 240">
                                                                <path class="st0" d="M104.4 103.9c-5.7 0-10.2 5-10.2 11.1s4.6 11.1 10.2 11.1c5.7 0 10.2-5 10.2-11.1.1-6.1-4.5-11.1-10.2-11.1zM140.9 103.9c-5.7 0-10.2 5-10.2 11.1s4.6 11.1 10.2 11.1c5.7 0 10.2-5 10.2-11.1s-4.5-11.1-10.2-11.1z"></path>
                                                                <path class="st0" d="M189.5 20h-134C44.2 20 35 29.2 35 40.6v135.2c0 11.4 9.2 20.6 20.5 20.6h113.4l-5.3-18.5 12.8 11.9 12.1 11.2 21.5 19V40.6c0-11.4-9.2-20.6-20.5-20.6zm-38.6 130.6s-3.6-4.3-6.6-8.1c13.1-3.7 18.1-11.9 18.1-11.9-4.1 2.7-8 4.6-11.5 5.9-5 2.1-9.8 3.5-14.5 4.3-9.6 1.8-18.4 1.3-25.9-.1-5.7-1.1-10.6-2.7-14.7-4.3-2.3-.9-4.8-2-7.3-3.4-.3-.2-.6-.3-.9-.5-.2-.1-.3-.2-.4-.3-1.8-1-2.8-1.7-2.8-1.7s4.8 8 17.5 11.8c-3 3.8-6.7 8.3-6.7 8.3-22.1-.7-30.5-15.2-30.5-15.2 0-32.2 14.4-58.3 14.4-58.3 14.4-10.8 28.1-10.5 28.1-10.5l1 1.2c-18 5.2-26.3 13.1-26.3 13.1s2.2-1.2 5.9-2.9c10.7-4.7 19.2-6 22.7-6.3.6-.1 1.1-.2 1.7-.2 6.1-.8 13-1 20.2-.2 9.5 1.1 19.7 3.9 30.1 9.6 0 0-7.9-7.5-24.9-12.7l1.4-1.6s13.7-.3 28.1 10.5c0 0 14.4 26.1 14.4 58.3 0 0-8.5 14.5-30.6 15.2z"></path>
                                                            </svg>
                                                        </div>
                                                        <span style="color:white">Discord Server</span>
                                                    </a>
                                                </div>
                                            </div> -->
                                </div>
                            </div>


                            <div class="daily-feeds card">

                                <div class="card-header bg-blue">
                                    <h3 class="h4"><i class="fa fa-wrench" style="color:white"></i> Useful information
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <center>
                                        <a href="https://discord.gg/84GWD9H" title="Join us on Discord">
                                            <img onContextMenu="return false;" width="auto" style="border-radius:7px;"
                                                src="https://discordapp.com/api/guilds/752151282491326504/embed.png?style=banner3">
                                        </a>
                                    </center>

                                    <!--
                                             <br>
                                            <div>
                                                <div style="display:inline;" id="discord-button">
                                                    <a href="https://discord.gg/84GWD9H" style="background-color:var(--primary-tr);padding: 4px 15px;" target="_blank">
                                                        <div style="font-size:25px; margin-right: 15px">
                                                            <i class="fa fa-sign-out"></i>
                                                        </div>
                                                        <span style="color:white">Direct Connect</span>
                                                    </a>
                                                </div>

                                                <div style="display:inline;" id="discord-button">
                                                    <a href="https://discord.gg/84GWD9H" target="_blank">
                                                        <div class="icon">
                                                            <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 245 240">
                                                                <path class="st0" d="M104.4 103.9c-5.7 0-10.2 5-10.2 11.1s4.6 11.1 10.2 11.1c5.7 0 10.2-5 10.2-11.1.1-6.1-4.5-11.1-10.2-11.1zM140.9 103.9c-5.7 0-10.2 5-10.2 11.1s4.6 11.1 10.2 11.1c5.7 0 10.2-5 10.2-11.1s-4.5-11.1-10.2-11.1z"></path>
                                                                <path class="st0" d="M189.5 20h-134C44.2 20 35 29.2 35 40.6v135.2c0 11.4 9.2 20.6 20.5 20.6h113.4l-5.3-18.5 12.8 11.9 12.1 11.2 21.5 19V40.6c0-11.4-9.2-20.6-20.5-20.6zm-38.6 130.6s-3.6-4.3-6.6-8.1c13.1-3.7 18.1-11.9 18.1-11.9-4.1 2.7-8 4.6-11.5 5.9-5 2.1-9.8 3.5-14.5 4.3-9.6 1.8-18.4 1.3-25.9-.1-5.7-1.1-10.6-2.7-14.7-4.3-2.3-.9-4.8-2-7.3-3.4-.3-.2-.6-.3-.9-.5-.2-.1-.3-.2-.4-.3-1.8-1-2.8-1.7-2.8-1.7s4.8 8 17.5 11.8c-3 3.8-6.7 8.3-6.7 8.3-22.1-.7-30.5-15.2-30.5-15.2 0-32.2 14.4-58.3 14.4-58.3 14.4-10.8 28.1-10.5 28.1-10.5l1 1.2c-18 5.2-26.3 13.1-26.3 13.1s2.2-1.2 5.9-2.9c10.7-4.7 19.2-6 22.7-6.3.6-.1 1.1-.2 1.7-.2 6.1-.8 13-1 20.2-.2 9.5 1.1 19.7 3.9 30.1 9.6 0 0-7.9-7.5-24.9-12.7l1.4-1.6s13.7-.3 28.1 10.5c0 0 14.4 26.1 14.4 58.3 0 0-8.5 14.5-30.6 15.2z"></path>
                                                            </svg>
                                                        </div>
                                                        <span style="color:white">Discord Server</span>
                                                    </a>
                                                </div>
                                            </div> -->
                                </div>
                            </div>


                            <div class="daily-feeds card">

                                <div class="card-header bg-success">
                                    <h3 class="h4"><i class="fa fa-money" style="color:white"></i> Money Goal</h3>
                                </div>
                                <div class="card-body">
                                    <center class="m-t-30"> <img onContextMenu="return false;"
                                            src="https://cdn2.iconfinder.com/data/icons/game-1-1/512/pouch-512.png"
                                            class="img-circle" width="150">
                                        <br><br>
                                        <?php
                                                    $sql = "SELECT * FROM `SF_Scripts` WHERE `sells` > 0 AND `public` = 1";
                                                    $r_query = $mysqli->query($sql);
                                                    $total_sells_money = 0;
                                                    while ($row = $r_query->fetch_assoc()) {
                                                        $script_data = json_decode($row['data'],true);
                                                        $total_sells_money = $total_sells_money+($script_data['price']*$row['sells']);
                                                    }

                                                    $money = $total_sells_money;
                                                    $preteuro = $money;
                                                    $pretlei = $money*4.8;


                                                    $goaleuro = MONEY_GOAL;
                                                    $goallei = MONEY_GOAL*4.8;
                                                    $goalmoney = $preteuro * 100 / $goaleuro;
                                                    echo "<center><b><font style='color:#27A243;'>" . number_format($pretlei,0). " lei <font style='color:white'>(" . number_format($preteuro,0). "€)</font>/".number_format($goallei,0)." lei <font style='color:white'>(".number_format($goaleuro,0)."€)</font></font></b></center>";
                                                ?>
                                        <div class="progress" style="height: 10px;margin-top:10px;">
                                            <div role="progressbar"
                                                style="background-color:var(--primary-color);width: <?php echo ceil($goalmoney);?>%; height: 10px;"
                                                aria-valuenow="<?php echo ceil($goalmoney);?>" aria-valuemin="0"
                                                aria-valuemax="100" class="progress-bar"></div>
                                        </div>
                                        <center class="m-t-30">
                                        </center>
                                    </center>
                                </div>
                                <div class="card-body no-padding">
                                    <div class="item">
                                        <div class="feed d-flex justify-content-between">
                                            <div class="feed-body d-flex justify-content-between">
                                                <div class="content">
                                                    <h5><a href="#">StayFrosty Community</a></h5>
                                                    <span>When the <b style="color:#00DF00">money goal</b> is completed,
                                                        i drop a <b style="color:var(--primary-color)">SCRIPT FOR
                                                            FREE</B></span>
                                                </div>
                                            </div>
                                            <div class="date text-right">
                                                <small><?php echo ceil($goalmoney)."% done";?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="daily-feeds card">
                                <div class="card-header bg-violet">
                                    <h3 class="h4">Developers Status</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>USER</th>
                                                <th>STATUS</th>
                                            </tr>
                                        </thead>

                                        <?php
                                                    $sql = "SELECT * FROM `SF_Users`";
                                                    $r_query = $mysqli->query($sql);
                                                    while ($row = $r_query->fetch_assoc()) {
                                                        $datatable = json_decode($row['groups'], true);
                                                        if($datatable['groups']){
                                                            if($datatable['groups']["developer"]){
                                                                $data = json_decode($row['data'], true);
                                                                echo '<tbody>
                                                                    <tr>
                                                                        <td class="text-bold-500"><a href="profile.php?user='.urlencode($row['username']).'">'.$row['username'].'</a></td>
                                                                        <td>';
                                                                if ($data['data']["available"]) {
                                                                    echo '<span class="badge bg-green" style="color:white">Available</span>';
                                                                } else {
                                                                    echo '<span class="badge bg-red" style="color:white">Busy</span>';
                                                                }
                                                                echo '</td>
                                                                    </tr>
                                                                </tbody>';
                                                            }
                                                        }
                                                    }
                                                    $r_query->close();
                                                ?>
                                    </table>
                                </div>
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