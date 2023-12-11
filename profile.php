<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">
<?php
        $title = "Profile";
        $page = "profile";

        require "config.php"; //CONFIG
        require "includes/head.inc.php"; // <HEAD>

        if (!Utils::is_user_logged()) {
            header("location: ../login.php"); exit();
        } else {
            if (isset($_GET['user'])) {
                $username = $_GET['user'];
            } else {
                $username = Session::exists("user");
            }
            $result = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE `username` = ?");
            $result -> bind_param('s',$username);
            $result -> execute();
            $rez = $result -> get_result();
            $player_data = $rez->fetch_assoc();
            if ($player_data) {
                $datatable = json_decode($player_data['groups'], true);
                $groups = $datatable['groups'];
                $data = json_decode($player_data['data'],true);
                $banned = json_decode($player_data['banned'],true);
                
                $likes = $utils->get_likes($player_data['user_id']);
                $is_liked = $utils->get_is_liked($_SESSION['id'],$player_data['user_id']);
            } else {
                header("location: index.php"); exit();
            }
            $result->close();

            if (isset($_POST['like_user']) && isset($_POST['user_to_like'])) {
                $to_user=$_POST['user_to_like'];
                $date = date("d-m-Y H:i:s");
                if ( $is_liked ) {
                    $mysqli->query("DELETE FROM `SF_Likes` WHERE `to` = '".$_POST['user_to_like']."' AND `from` = '".$_SESSION['id']."'");
                    $utils->add_log(Session::exists("user"),"<div style='text-align:center;border:1px solid var(--primary-tr);border-radius:10px;box-shadow: 0 0 20px var(--primary-tr);padding:3px 1px;'><a href='profile.php?user=".$_SESSION['user']."'>".$_SESSION['user']."</a> disliked <a href='profile.php?user=".$player_data['username']."'>".$player_data['username']."</a></div>");
                } else {
                    $query = $mysqli->prepare("INSERT INTO SF_Likes(`from`, `to`, `date`) VALUES (?,?,?)");
                    $query -> bind_param('iis',$_SESSION['id'],$to_user,$date);
                    $query -> execute();
                    $query -> close();
                    $utils->add_log(Session::exists("user"),"<div style='text-align:center;border:1px solid var(--primary-color);border-radius:10px;box-shadow: 0 0 20px var(--primary-color);padding:3px 1px;'><a href='profile.php?user=".$_SESSION['user']."'>".$_SESSION['user']."</a> liked <a href='profile.php?user=".$player_data['username']."'>".$player_data['username']."</a></div>");
                }
                echo '<meta http-equiv="refresh" content="0">';
            }
        }
    ?>

<?php 
if (isset($_POST['UNBAN_USER'])) {
    $user_to_unban=$_POST['UNBAN_USER'];
    $json = '{"banned":false}';
    $mysqli->query("UPDATE SF_Users SET banned = '$json' WHERE user_id = '$user_to_unban'") or die(mysqli_error($mysqli));
    echo '<meta http-equiv="refresh" content="0">';
}
if (isset($_POST['BAN_USER'])) {
    $user_to_ban=$_POST['BAN_USER'];
    $reason=$_POST['reason'];

    $selected = $_POST['expire'];
    if ($selected=="D_1") {
        $expire = time()+(3600*(24*1));
    } else if ($selected=="D_2") {
        $expire = time()+(3600*(24*2));
    } else if ($selected=="D_3") {
        $expire = time()+(3600*(24*3));
    } else if ($selected=="D_4") {
        $expire = time()+(3600*(24*4));
    } else if ($selected=="D_5") {
        $expire = time()+(3600*(24*5));
    } else if ($selected=="D_6") {
        $expire = time()+(3600*(24*6));
    } else if ($selected=="D_7") {
        $expire = time()+(3600*(24*7));
    } else if ($selected=="D_21") {
        $expire = time()+(3600*(24*28));
    } else if ($selected=="Y_1") {
        $expire = time()+(3600*(24*365));
    } else {
        $expire = time()+(3600*(24*365*50));
    }

    $json = '{"banned":true,"from":"'.Session::exists("user").'","reason":"'.$reason.'","date":'.time().',"expires":'.$expire.'}';
    $mysqli->query("UPDATE SF_Users SET banned = '$json' WHERE user_id = '$user_to_ban'") or die(mysqli_error($mysqli));    
    echo '<meta http-equiv="refresh" content="0">';
}

if (Utils::is_user_logged()) {?>
<div id="ban_user" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal fade show" id="large-Modal" tabindex="-1" role="dialog"
        style="z-index: 1050; display: block; padding-right: 20px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo $banned['banned'] ? "UNBAN USER" : "BAN USER";?></h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <?php if ($banned['banned']) {?>
                                        <form id="banunban" method="POST">
                                            <input type="hidden" name="UNBAN_USER" value="<?php echo htmlspecialchars($player_data['user_id']);?>" >
                                            <b>Banned from</b>: <a
                                                href="profile.php?user=<?php echo $banned['from']; ?>"><?php echo $banned['from']; ?></a><br />
                                            <b>Reason</b>: <?php echo $banned['reason']; ?><br />
                                            <b>Banned date</b>: <?php echo date("d/m/Y h:s", $banned['date'])?><br />
                                            <b>Banned expires</b>:
                                            <?php echo date("d/m/Y h:s", $banned['expires'])?><br /><br />
                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                <button type="submit" class="btn btn-info btn-block glow mr-sm-1 mb-1">UNBAN
                                                USER</button>
                                            </div>
                                        </form>
                                    <?php } else {?>
                                    <form method="POST">
                                        <input type="hidden" name="BAN_USER" value="<?php echo htmlspecialchars($player_data['user_id']);?>" >
                                        <div class="row">
                                            <label class="col-md-3 app_style">
                                                <b style="font-weight: bold;">Reason</b>
                                            </label>
                                            <div class="col-md-9 app_style">
                                                <input required type="text" placeholder="Why you ban him?" name="reason" class="form-control" />
                                            </div>
                                        </div></br>
                                        <div class="row">
                                            <label class="col-md-3 app_style">
                                                <b style="font-weight: bold;">Expire</b>
                                            </label>
                                            <div class="col-md-9 app_style">
                                                <select name="expire" class="form-control">
                                                    <optgroup label="Temporary Ban Options"> 
                                                        <option value="D_1">1 Day (<?php echo date("d-m-Y", time()+((60*60)*24)); ?> 12:00 AM)</option> 
                                                        <option value="D_2">2 Days (<?php echo date("d-m-Y", time()+((60*60)*48)); ?> 12:00 AM)</option> 
                                                        <option value="D_3">3 Days (<?php echo date("d-m-Y", time()+((60*60)*72)); ?> 12:00 AM)</option> 
                                                        <option value="D_4">4 Days (<?php echo date("d-m-Y", time()+((60*60)*96)); ?> 12:00 AM)</option> 
                                                        <option value="D_5">5 Days (<?php echo date("d-m-Y", time()+((60*60)*120)); ?> 12:00 AM)</option> 
                                                        <option value="D_6">6 Days (<?php echo date("d-m-Y", time()+((60*60)*144)); ?> 12:00 AM)</option> 
                                                        <option value="D_7" selected="selected">1 Week (<?php echo date("d-m-Y", time()+((60*60)*168)); ?> 12:00 AM)</option> 		
                                                        <option value="D_21">1 Month (<?php echo date("d-m-Y", time()+((60*60)*744)); ?> 12:00 AM)</option> 
                                                        <option value="Y_1">1 Year (<?php echo date("d-m-Y", time()+((60*60)*8765)); ?> 12:00 AM)</option> 
                                                    </optgroup> 
                                                    <optgroup label="Permanent Ban Options"> 
                                                        <option value="PERMANENT">Permanent - Never Lift Ban</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div></br>
                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                            <button type="submit" class="btn btn-info btn-block glow mr-sm-1 mb-1">BAN
                                                USER</button>
                                        </div>
                                    </form>
                                    <?php }?>
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


<body>
    <div class="page">
        <?php require "includes/navbar.inc.php"; //NAVBAR ?>
        <div class="content-inner">
            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">Profile > <i><?php echo $username; ?></i></h2>
                </div>
            </header>

            <section class="tables">

                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-3 _left-side">
                            <?php if ($banned['banned']) {
                                if (time()>$banned['expires'] ) {
                                    $user_to_unban=$player_data['user_id'];
                                    $json = '{"banned":false}';
                                    $mysqli->query("UPDATE SF_Users SET banned = '$json' WHERE user_id = '$user_to_unban'") or die(mysqli_error($mysqli));
                                    echo '<meta http-equiv="refresh" content="0">';
                                }
                                ?>
                            <div class="card">
                                <div class="card-header bg-red">
                                    <b><i class="fa fa-ban" aria-hidden="true"></i> BANNED</b>
                                </div>

                                <div class="card-body">
                                    <b>Banned from</b>: <a
                                        href="profile.php?user=<?php echo $banned['from']; ?>"><?php echo $banned['from']; ?></a><br />
                                    <b>Reason</b>: <?php echo $banned['reason']; ?><br />
                                    <b>Banned date</b>: <?php echo date("d/m/Y h:s", $banned['date'])?><br />
                                    <b>Banned expires</b>: <?php echo date("d/m/Y h:s", $banned['expires'])?><br />
                                </div>
                            </div>
                            <?php }?>
                            <div class="card">
                                <div class="card-body" style="border-radius:10px;border:2px solid var(--primary-tr);">
                                    <center class="m-t-30">
                                        <img src="<?php echo $player_data['avatar'];?>"
                                            class="img-circleeeeeeeeeeeeeeeeeeeee" width="150" /> <br />
                                        <br />
                                        <h4 class="card-title m-t-10">
                                            <?php 
                                                    if (Utils::is_online($player_data['last_online'])) {
                                                        echo '<i class="fa fa-circle fa-fw" style="color: green;"></i>';
                                                    } else {
                                                        echo '<i class="fa fa-circle fa-fw" style="color: red;"></i>';
                                                    }
                                                    ?>

                                            <?php 
                                                        echo $username;
                                                        if( $username == Session::exists("user") ) {
                                                            echo '<p style="font-size:15px;"><span style="color:var(--color-principaly) !important;">Balance:</span> <b style="color:#3ADD00;"> '.Utils::balanceFormat($player_data['balance']).'&euro;</b></p>';
                                                        }
                                                    ?>

                                            <div id="likes">
                                                <i class="fa fa-thumbs-up" style="color:#0280F6;"
                                                    aria-hidden="true"></i><span>&nbsp;<?php echo count($utils->get_likes($player_data['user_id']));?></span>
                                            </div>
                                        </h4>

                                        <?php
                                                    echo Utils::get_groups_design($groups,"","global.view");
                                                ?>

                                        <br />
                                        <br />

                                        <div class="form-group">
                                            <?php
                                                if( $username == Session::exists("user") ) {
                                                    echo '<a data-toggle="modal" data-target="#edit_profile" href="#edit_profile""><button type="submit" class="btn btn-info">EDIT</button></a>';
                                                    echo '&nbsp;<a data-toggle="modal" data-target="#add_balance" href="#add_balance""><button type="submit" class="btn btn-primary">ADD BALANCE</button></a>';
                                                } else {
                                                  echo '<a href="404.html"><button type="submit" class="btn btn-danger">REPORT</button></a>';

                                                  if (Utils::is_user_permission($user_groups['groups'],"bans")) {
                                                    echo '&nbsp;<a data-toggle="modal" data-target="#ban_user" href="#ban_user""><button type="submit" class="btn btn-info">'.($banned["banned"] ? "UNBAN" : "BAN").'</button></a>';
                                                  }

                                                echo '
                                                  <form method="post" style="margin-top:10px;">
                                                    <input type="hidden" name="user_to_like" value="'.$player_data['user_id'].'">
                                                    <input type="submit" class="btn btn-hudcolor special-btn" name="like_user" value="'.($is_liked ? "DISLIKE" : "LIKE").'"/>
                                                  </form>';
                                                }
                                                ?>
                                        </div>
                                    </center>
                                </div>
                            </div>
                        </div>

                        <!-- Column -->
                        <!-- Column -->
                        <div class="col-md-9 _right-side">
                            <div class="card">
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#home"
                                            role="tab" aria-selected="true">Profile</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#logs" role="tab"
                                            aria-selected="false">Logs</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bought" role="tab"
                                            aria-selected="false">Bought</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active show" id="home" role="tabpanel">
                                        <div class="card-body">
                                            <div class="card-block">
                                                <div class="view-info">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="general-info">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <table class="table m-0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <th scope="row">User ID</th>
                                                                                    <td>
                                                                                        <?php echo $player_data['user_id'];?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th scope="row">Username</th>
                                                                                    <td>
                                                                                        <?php echo $username;?>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th scope="row">Email</th>
                                                                                    <td>
                                                                                        <?php echo $player_data['email'];?>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th scope="row">Joined</th>
                                                                                    <td>
                                                                                        <?php echo $player_data['first_login'];?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th scope="row">Last Login</th>
                                                                                    <td>
                                                                                        <a class="text-muted" data-placement="bottom" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="<?php echo $player_data['last_login'];?>"><i
                                                                                                class="fa fa-clock-o"></i>
                                                                                            <?php echo Utils::time_elapsed_string('@'.strtotime($player_data['last_login']));?></a>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th scope="row">Last Online</th>
                                                                                    <td>
                                                                                    <a class="text-muted" data-placement="bottom" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="<?php echo $player_data['last_online'];?>"><i
                                                                                                class="fa fa-clock-o"></i>
                                                                                            <?php echo Utils::time_elapsed_string('@'.strtotime($player_data['last_online']));?></a>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th scope="row">Verified</th>
                                                                                    <td>
                                                                                        <?php
                                                                                                if ($player_data['verified']=="yes") {
                                                                                                  echo '<span style ="">Yes</span>';
                                                                                                } else {
                                                                                                  echo '<span class="badge bg-orange">No</span>';
                                                                                                }
                                                                                              ?>
                                                                                    </td>
                                                                                </tr>

                                                                                <?php if (Utils::is_user_permission($groups,"dev")) {?>
                                                                                <tr>
                                                                                    <th scope="row">Status</th>
                                                                                    <td>
                                                                                        <?php
                                                                                                    if ($data['data']["available"]) {
                                                                                                        echo '<span class="badge bg-green" style="color:white">Available</span>';
                                                                                                    } else {
                                                                                                        echo '<span class="badge bg-red" style="color:white">Busy</span>';
                                                                                                    }
                                                                                                    ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php }?>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <!-- end of row -->
                                                            </div>
                                                            <!-- end of general info -->
                                                        </div>
                                                        <!-- end of col-lg-12 -->
                                                    </div>
                                                    <!-- end of row -->
                                                </div>
                                                <!-- end of view-info -->
                                            </div>
                                        </div>
                                    </div>
                                    <!--second tab-->
                                    <div class="tab-pane" id="logs" role="tabpanel">
                                        <div class="card-body">
                                            <div class="feed-element" style="padding: 5px;">
                                                <?php
                                                            $result = $mysqli->prepare("SELECT * FROM `SF_Logs` WHERE author = ? ORDER BY id desc LIMIT 10");
                                                            $result -> bind_param('s',$username);
                                                            $result -> execute();
                                                            $rez = $result -> get_result();
                                                            while ($user_logs = $rez->fetch_assoc()) {
                                                                echo '<div class="_fhc media-body">
                                                                        <small class="pull-right"><i class="fa fa-clock-o"></i> '.Utils::time_elapsed_string('@'.strtotime($user_logs['posted'])).'</small>
                                                                        '.$user_logs['log'].'<br />
                                                                        <small class="text-muted">'.$user_logs['posted'].'</small>
                                                                    </div>';
                                                            }
                                                            $result->close();
                                                        ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -->
                                    <div class="tab-pane" id="bought">

                                        <div class="table-responsive">
                                            <center>
                                                If you don't receive the account you can make a <a href="ticket.php">TICKET</a> or you can contact at discord. (<b>HackerG ﾉ sp1nE#8389</b>)
                                            </center><table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Account</th>
                                                    <th><i class="fa fa-address-card-o"></i> Username</th>
                                                    <th><i class="fa fa-dashboard"></i> Password</th>
                                                    <th><i class="fa fa-bars"></i> Email</th>
                                                    <th><i class="fa fa-history"></i> Email password</th>
                                                    <th><i class="fa fa-gear fa-spin"></i> Options</th>
                                                </tr>
                                                </thead>
                                                <style>
                                                    input:read-only {
                                                        background-color: blue;
                                                        color: #BEC5CB;
                                                    }
                                                </style>



                                                <tbody><tr id="1"><td style="vertical-align:middle;"><img src="https://cdn.freebiesupply.com/images/large/2x/steam-logo-transparent.png" width="30"></td><td style="vertical-align:middlel text-align:center;"><input class="form-control" type="text" name="details" style="background: #151A1E" readonly="" value="armandusername"></td> <td style="vertical-align:middle"><input class="form-control" type="text" name="details" style="background: #151A1E" readonly="" value="armandpassword"></td> <td style="vertical-align:middle"><input class="form-control" type="text" name="details" style="background: #151A1E" readonly="" value="No"></td><td style="vertical-align:middle"><input class="form-control" type="text" name="details" style="background: #151A1E" readonly="" value="No"></td><td style="vertical-align:middle"> <button disabled="">delete</button> </td><td style="vertical-align:middle"></td></tr><tr id="1"><td style="vertical-align:middle;"><img src="https://www.freepnglogos.com/uploads/netflix-logo-circle-png-5.png" width="30"></td><td style="vertical-align:middlel text-align:center;"><input class="form-control" type="text" name="details" style="background: #151A1E" readonly="" value="efb1995@comcast.net"></td> <td style="vertical-align:middle"><input class="form-control" type="text" name="details" style="background: #151A1E" readonly="" value="Brady524"></td> <td style="vertical-align:middle"><input class="form-control" type="text" name="details" style="background: #151A1E" readonly="" value="No"></td><td style="vertical-align:middle"><input class="form-control" type="text" name="details" style="background: #151A1E" readonly="" value="No"></td><td style="vertical-align:middle"> <button disabled="">delete</button> </td><td style="vertical-align:middle"></td></tr>
                                                </tbody>
                                            </table>
                                        </div>


                                    </div>
                                    <!-- -->
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
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