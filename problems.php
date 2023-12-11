<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php
        $title = "Problems";
        $page = "problems";
        require "config.php"; //CONFIG
        require "includes/head.inc.php"; // <HEAD>

        if (!Utils::is_user_logged()) {
            header("location: ../login.php"); exit();
        }
    ?>

<body>
    <div class="page">
        <?php require "includes/navbar.inc.php"; //NAVBAR
                $username = Session::exists("user");
                if (!Utils::is_user_permission($user_groups['groups'],"administration.problems")) {
                    header("location: index.php"); exit();
                }
            ?>
        <div class="content-inner">

            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">Profile > <i>Problems</i></h2>
                </div>
            </header>
            <section class="tables">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <?php
                                            $numar_erori=0;
                                            $file = file_get_contents("./template/problems.json");
                                            if ($file) {
                                                $file_data = json_decode($file, true);
                                                foreach($file_data['errors'] as $eroare) {
                                                    $numar_erori++;
                                                    $errors[] = $eroare;
                                                }

                                        ?>
                                <div class="card-header" style="background-color:var(--primary-color);">
                                    <h3 class="h4"><i class="fa fa-users" style="color:white"></i> All Errors
                                        [<?php echo $numar_erori;?>]</h3>
                                </div>
                                <div style="padding:10px 10px;background-color:#31333A;border:2px solid var(--primary-tr);"
                                    id="errors">
                                    <div style="padding:20px 20px;background-color:#191B20;border-radius:7px;border: 1px solid var(--color2-white-black);"
                                        id="errors">

                                        <?php
                                                    if (isset($_POST['delete_error'])) {
                                                        if (Utils::is_user_permission($user_groups['groups'],"administration.problems.delete")) {
                                                            unset($errors[$_POST['id_eroare']]);
                                                            $file_data['errors'] = $errors;
                                                            file_put_contents("./template/problems.json",json_encode($file_data,true));
                                                            header("location: problems.php"); exit();
                                                        }
                                                    }
                                                    if (isset($_POST['delete_all'])) {
                                                        if (Utils::is_user_permission($user_groups['groups'],"administration.problems.delete")) {
                                                            $file_data['errors'] = [];
                                                            file_put_contents("./template/problems.json",json_encode($file_data,true));
                                                            header("location: problems.php"); exit();
                                                        }
                                                    }
                                                    if (isset($errors)) {
                                                        krsort($errors);
                                                        foreach($errors as $key => $error) {
                                                            switch($error['priority']) {
                                                                case 3: $color='#DC3545';
                                                                break;
                                                                case 2: $color='#F5A817';
                                                                break;
                                                                default: $color='#DDDDDD';
                                                            }
                                                ?>
                                        <form id="form_errors" method="post">
                                            <input type="hidden" name="id_eroare" value="<?php echo $key;?>">
                                            <p
                                                style="padding:10px 10px;border-radius:10px;border:2px solid <?php echo $color;?>;">
                                                <span
                                                    style="color:<?php echo $color;?>;"><b>(<?php echo $error['time']?>)</b></span>
                                                <?php echo $error['problem'];?>
                                                <input type="submit" value="&times;" name="delete_error"
                                                    style="color:red;background-color:transparent;border:none;float:right;font-size:27px;margin-top: -5px;" />
                                            </p>
                                        </form>
                                        <?php
                                                        }
                                                    } else {
                                                        echo "No current errors...";
                                                    }
                                                ?>
                                    </div>
                                    <form id="form_del_errors" method="post">
                                        <input type="submit" class="btn btn-info btn-danger glow mr-sm-1 mb-1"
                                            style="margin-top:10px;" value="Delete All" name="delete_all" />
                                    </form>
                                </div>
                                <script>
                                const errors_del = document.querySelectorAll("#form_errors");
                                for (error_del of errors_del) {
                                    error_del.addEventListener('submit', function(e) {
                                        if (!confirm("You are sure?")) {
                                            e.preventDefault();
                                        }
                                    })
                                }
                                document.querySelector("#form_del_errors").addEventListener('submit', function(e) {
                                    if (!confirm("You are sure?")) {
                                        e.preventDefault();
                                    }
                                })
                                </script>
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