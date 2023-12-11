<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php 
        $title = "Staff";
        $page = "staff";
        require "config.php"; //CONFIG
        require "includes/head.inc.php"; // <HEAD>
    ?>

<body>
    <div class="page">
        <?php require "includes/navbar.inc.php"; //NAVBAR 

            $sql = "SELECT * FROM `SF_Users`"; 
            $r_query = $mysqli->query($sql); 
            $admins=0;
            $devs=0;
            $helpers=0;
            while ($row = $r_query->fetch_assoc()) {
                $datatable = json_decode($row['groups'], true);
                if($datatable['groups']["developer"]){
                    $devs++;
                }
                if($datatable['groups']["admin"]){
                    $admins++;
                }
                if($datatable['groups']["helper"]){
                    $helpers++;
                }
                $tables[] = $row;
            }
            $r_query->close();
            ?>
        <div class="content-inner">
            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">Home > <i>Staff</i></h2>
                </div>
            </header>
            <section class="tables">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tabs-container">
                                        <ul class="nav nav-tabs customtab nav-fill">
                                            <li class="nav-item"><a class="nav-link active show" data-toggle="tab"
                                                    href="#tab-devs" aria-expanded="true">Developers
                                                    [<?php echo $devs;?>/1]</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                    href="#tab-admins" aria-expanded="false">Admins
                                                    [<?php echo $admins."/".MAX_admin;?>]</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                    href="#tab-helpers" aria-expanded="false">Helpers
                                                    [<?php echo $helpers."/".MAX_helper;?>]</a></li>
                                        </ul>
                                    </div>
                                    <div class="tab-content">
                                        <div id="tab-admins" class="tab-pane">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Status</th>
                                                            <th>Avatar</th>
                                                            <th>Username</th>
                                                            <th>Group</th>
                                                            <th>Last Online</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php
                                                                if (isset($_GET['delete_staff'])) {
                                                                    if (Session::exists('user') && Utils::is_user_permission($user_groups['groups'],"staff.delete") ) {
                                                                        $user_to_remove = $_GET['delete_staff'];
                                                                        $query = $mysqli->prepare("UPDATE SF_Users SET groups=? WHERE `user_id` = ?");
                                                                        $query -> bind_param('si',$default_groups,$user_to_remove);
                                                                        $query -> execute();
                                                                        $query -> close();
                                                                    }
                                                                }
                                                                foreach($tables as $table) {
                                                                    $datatable = json_decode($table['groups'], true);
                                                                    if($datatable['groups']["admin"]){
                                                                        echo '<tr>';
                                                                        if (Utils::is_online($table['last_online'])) {
                                                                            echo '<td><span class="badge bg-green">Online</span></td>';
                                                                        } else {
                                                                            echo '<td><span class="badge bg-red">Offline</span></td>';
                                                                        }

                                                                        echo '<td><img alt="image" class="img-circleeeeeeeeeeeeeeeeeeeee" style="width: 42px;height: 42px;  border-radius: 50% !important;" src="'.$table["avatar"].'" /></td>
                                                                            <td><a href="profile.php?user='.urlencode($table['username']).'">'.$table["username"].'</a></td>
                                                                            <td style="max-width: 50%;">
                                                                            ';
                                                                            echo Utils::get_groups_design($datatable['groups'],"","global.view");
                                                                            echo '
                                                                            </td>tsets
                                                                            <td data-placement="bottom" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="'.$table['last_online'].'"><i class="fa fa-clock-o"></i> '.Utils::time_elapsed_string('@'.strtotime($table['last_online'])).'</td>
                                                                            <td>
                                                                                <div class="action_buttons" style="font-size:6px;">
                                                                                    <a href="?edit_staff='.$table["user_id"].'" style="color:green;background-color:transparent;border:none;font-size:20px;border:2px solid var(--border-default);padding:0px 1px;border-radius:3px;font-size:17px;">&#10000;</a>
                                                                                    <a href="?delete_staff=1'.$table["user_id"].'" style="color:red;background-color:transparent;border:none;font-size:20px;border:2px solid var(--border-default);padding:0px 2px;border-radius:3px;font-size:17px;">&#10006;</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>';
                                                                    }
                                                                }
                                                            ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="tab-helpers" class="tab-pane">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Status</th>
                                                            <th>Avatar</th>
                                                            <th>Username</th>
                                                            <th>Groups</th>
                                                            <th>Last Online</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                                foreach($tables as $table) {
                                                                    $datatable = json_decode($table['groups'], true);
                                                                    if($datatable['groups']["helper"]){
                                                                        echo '<tr>';
                                                                        if (Utils::is_online($table['last_online'])) {
                                                                            echo '<td><span class="badge bg-green">Online</span></td>';
                                                                        } else {
                                                                            echo '<td><span class="badge bg-red">Offline</span></td>';
                                                                        }
                                                                        
                                                                        echo '<td><img alt="image" class="img-circleeeeeeeeeeeeeeeeeeeee" style="width: 42px;height: 42px;  border-radius: 50% !important;" src="'.$table["avatar"].'" /></td>
                                                                            <td><a href="profile.php?user='.urlencode($table['username']).'">'.$table["username"].'</a></td>
                                                                            <td>
                                                                            ';
                                                                        
                                                                            echo '
                                                                               '.Utils::get_groups_design($datatable['groups'],"","global.view").'
                                                                            </td>
                                                                            <td data-placement="bottom" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="'.$table['last_online'].'"><i class="fa fa-clock-o"></i> '.Utils::time_elapsed_string('@'.strtotime($table['last_online'])).'</td>
                                                                         <td>
                                                                                <div class="action_buttons" style="font-size:6px;">
                                                                                    <a href="?edit_staff='.$table["user_id"].'" style="color:green;background-color:transparent;border:none;font-size:20px;border:2px solid var(--border-default);padding:0px 1px;border-radius:3px;font-size:17px;">&#10000;</a>
                                                                                    <a href="?delete_staff=1'.$table["user_id"].'" style="color:red;background-color:transparent;border:none;font-size:20px;border:2px solid var(--border-default);padding:0px 2px;border-radius:3px;font-size:17px;">&#10006;</a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>';
                                                                    }
                                                                }
                                                            ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="tab-devs" class="tab-pane active show">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Status</th>
                                                            <th>Avatar</th>
                                                            <th>Username</th>
                                                            <th>Group</th>
                                                            <th>Last Online</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach($tables as $table) {
                                                                $datatable = json_decode($table['groups'], true);
                                                                if($datatable['groups']["developer"]){
                                                                    
                                                                    echo '<tr>';

                                                                    if (Utils::is_online($table['last_online'])) {
                                                                        echo '<td><span class="badge bg-green">Online</span></td>';
                                                                    } else {
                                                                        echo '<td><span class="badge bg-red">Offline</span></td>';
                                                                    }
                                                                    echo '
                                                                    <td><img alt="image" class="img-circleeeeeeeeeeeeeeeeeeeee" style="width: 42px;height: 42px;  border-radius: 50% !important;" src="'.$table["avatar"].'" /></td>
                                                                    <td><a href="profile.php?user='.urlencode($table['username']).'">'.$table["username"].'</a></td>
                                                                    <td>
                                                                    
                                                                    ';
                                                                    echo Utils::get_groups_design($datatable['groups'],"","global.view");
                                                                    echo '
                                                                        
                                                                    </td>
                                                                        <td data-placement="bottom" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="'.$table['last_online'].'"><i class="fa fa-clock-o"></i> '.Utils::time_elapsed_string('@'.strtotime($table['last_online'])).'</td>
                                                                    </tr>';
                                                                }
                                                            }
                                                            ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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