<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php
        $title = "Search";
        $page = "search";
        require "config.php"; //CONFIG
        require "includes/head.inc.php"; // <HEAD>

    ?>

<body>
    <div class="page">
        <?php require "includes/navbar.inc.php"; //NAVBAR ?>
        <div class="content-inner">
            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">Home > <i>Search</i></h2>
                </div>
            </header>
            <section class="tables">
                <div class="container-fluid">
                    <div class="row">
                        <?php if (!Utils::is_user_logged()) { ?>
                        <div class="col-lg-12">
                            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                You are not logged in.
                            </div>
                        </div>
                        <?php } ?>

                        <div class="col-lg-12">
                            <div class="card">
                                <h3 class="card-header b-b"><i class="fa fa-search"></i>&nbsp; Search a user</h3>
                                <div class="card-body">
                                    <div class="col-sm-12">
                                        <form method="POST">
                                            <div class="row">
                                                <label class="col-md-3 app_style">
                                                    <b style="font-weight: bold;">User name</b>
                                                </label>
                                                <div class="col-md-9 app_style">
                                                    <input type="text" required placeholder="Plase enter user name!"
                                                        name="search_name"
                                                        value="<?php if (isset($_POST['search_name'])) echo $_POST['search_name']; ?>"
                                                        class="form-control" />
                                                </div>
                                            </div>
                                            <br />
                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                <button type="submit" name="submit"
                                                    class="btn btn-block butonas">Search</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($_POST['search_name'])) {?>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                            role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            Only the first 10 results are displayed. If you can't find the player you're
                                            looking for, write a larger part of its name.
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Avatar</th>
                                                        <th>Username</th>
                                                        <th>Groups</th>
                                                        <th>Banned</th>
                                                        <th>Last Login</th>
                                                        <th>Last Online</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                                $param = '%'.$_POST["search_name"].'%';
                                                                $result = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE `username` LIKE ? ORDER BY `user_id` desc LIMIT 15");
                                                                $result -> bind_param('s',$param);
                                                                $result -> execute();
                                                                $rez = $result -> get_result();
                                                                $nr=0;
                                                                while ($user_dt = $rez->fetch_assoc()) {
                                                                    $nr++;
                                                                    $datatable = json_decode($user_dt['groups'], true);
                                                                    echo '
                                                                    <tr>
                                                                        <th scope="row">'.$nr.'</th>
                                                                        <td><img alt="image" class="img-circleeeeeeeeeeeeeeeeeeeee" style="width: 42px;height: 42px;  border-radius: 50% !important;" src="'.$user_dt["avatar"].'" /></td>
                                                                        <td><a href="profile.php?user='.urlencode($user_dt['username']).'">'.$user_dt["username"].'</a></td>
                                                                        <td>'.Utils::get_groups_design($datatable['groups'],"","global.view").'</td>
                                                                        <td >'. (json_decode($user_dt['banned'],true)['banned'] ? "<span style='color:red'>YES</span>" : "NO") .'</td>
                                                                        <td><span data-placement="bottom" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="'.$user_dt['last_login'].'"><i class="fa fa-clock-o"></i> '.Utils::time_elapsed_string('@'.strtotime($user_dt['last_login'])).'</span></td>
                                                                        <td><span data-placement="bottom" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="'.$user_dt['last_online'].'"><i class="fa fa-clock-o"></i> '.Utils::time_elapsed_string('@'.strtotime($user_dt['last_online'])).'</span></td>
                                                                    </tr>
                                                                    ';
                                                                }
                                                                $result->close();
                                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
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