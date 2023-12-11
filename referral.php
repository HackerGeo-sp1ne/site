<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">
<?php
        $title = "Referral";
        $page = "referral";
        require "config.php"; //CONFIG
        require "includes/head.inc.php"; // <HEAD>

        if (!Utils::is_user_logged()) {
            header("location: ../login.php"); exit();
        }
    ?>
<body>
    <div class="page">
        <?php require "includes/navbar.inc.php"; //NAVBAR
                $username = $_SESSION['user'];
                $reff = new Referral($username);

                if (isset($_POST['add_new_promocode']) && isset($_POST['discount_val']) ) {
                    $reff->create($_POST['discount_val']);
                }
            ?>
        <div id="add_code" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal fade show" id="large-Modal" tabindex="-1" role="dialog"
                style="z-index: 1050; display: block; padding-right: 20px;">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Add Code</h3>
                        </div>
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="col-sm-12">
                                            <form method="POST">
                                                <div class="row">
                                                    <label class="col-md-3 app_style">
                                                        <b style="font-weight: bold;">Discount</b>
                                                    </label>
                                                    <div class="col-md-9 app_style">
                                                        <select name="discount_val" class="form-control">
                                                            <option disabled value="15">15%</option>
                                                            <option value="10">10%</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                    <button type="submit" name="add_new_promocode"
                                                        class="btn btn-info btn-block glow mr-sm-1 mb-1">ADD
                                                        PROMOCODE</button>
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



        <div class="content-inner">

            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">Profile > <i>Referral</i></h2>
                </div>
            </header>
            <section class="tables">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header" style="background-color:var(--primary-color);">
                                    <h3 class="h4"><i class="fa fa-users" style="color:white"></i> Referral Data</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Referral Type</th>
                                                    <th>URL</th>
                                                    <th>Clicks</th>
                                                    <th>Signups</th>
                                                    <th>Options</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">1</th>
                                                    <td><span style="color:red">Click &amp; Register</span></td>
                                                    <td><input class="form-control" onClick="this.select();" type="text"
                                                            id="ref_link" style="background: #171921" readonly=""
                                                            value="<?php echo SITE_URL."/register.php?r=".urlencode($username);?>">
                                                    </td>
                                                    <td><b><?php echo $reff->get_url("clicks");?></b></td>
                                                    <td><b><?php echo $reff->get_url("signups");?></b></td>
                                                    <td><button
                                                            style="background-color:black;border:none;border:3px solid var(--primary-tr);border-radius:5px;height:30px;"
                                                            onclick="copyText('ref_link')"><span
                                                                style="color:var(--primary-color)"><i
                                                                    class="fa fa-fw fa-copy"></i> Copy
                                                                URL</span></button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div><br>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Referral Type</th>
                                                    <th>CODE</th>
                                                    <th>Value</th>
                                                    <th>Uses</th>
                                                    <th>Options</th>
                                                </tr>
                                            </thead>
                                            <?php
                                                        if (isset($_POST['delete_code'])) {
                                                            $reff->delete($_POST['delete_code']);
                                                        }

                                                        $nr = 0;
                                                        $pt_foreach=$reff->get_referrals();
                                                        foreach($pt_foreach as $r_key=>$r_data) {
                                                            $nr++;
                                                            echo '
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">'.$nr.'</th>
                                                                    <td><span style="color:green">Promo Code</span></td>
                                                                    <td><input class="form-control" onClick="this.select();" type="text" id="ref_promo_'.$nr.'" style="background: #171921;" readonly="" value="'.strtolower($username).'_'.$r_key.'"></td>
                                                                    <td><b>'.$r_data['value'].'% discount</b></td>
                                                                    <td><b>'.$r_data['uses'].'</b></td>
                                                                    <td>
                                                                        <button style="background-color:black;border:none;border:3px solid var(--primary-tr);border-radius:5px;height:30px;" onclick="copyText(`ref_promo_'.$nr.'`)"><span style="color:var(--primary-color)"><i class="fa fa-copy"></i> Copy code</span></button>
                                                                        <form style="display:inline;" method="post">
                                                                            <button type="submit" name="delete_code" value="'.$r_key.'" style="background-color:black;border:none;border:3px solid #FF000070;border-radius:5px;height:30px;"><span style="color:#FF0000"><i class="fa fa-trash"></i> Remove</span></button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            ';
                                                        }

                                                        if (count($pt_foreach) < 2) {
                                                            echo '<tbody>
                                                                    <tr>
                                                                        <th scope="row"></th>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>
                                                                            <button data-toggle="modal" data-target="#add_code" href="#add_code" style="background-color:black;border:none;border:3px solid #00AA0070;border-radius:5px;height:30px;"><span style="color:#00AA00"><i class="fa fa-plus"></i> Add PromoCode</span></button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            ';
                                                        }
                                                    ?>
                                        </table>
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