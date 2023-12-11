<!doctype html>
<html id="color-picker" data-theme="default" lang="en-US">

<?php
        $title = "Store";
        $page = "store";
        require "config.php"; //CONFIG
        require "includes/head.inc.php"; // <HEAD>
    ?>
<style>
.container {
    position: relative;
    width: 50%;
}

.image {
    border: 2px solid var(--primary-tr);
    border-radius: 4px;
    opacity: 1;
    display: block;
    width: 200px;
    height: auto;
    transition: .5s ease;
    backface-visibility: hidden;
}

.middle {
    transition: .5s ease;
    opacity: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    text-align: center;
}

.container:hover .image {
    opacity: 0.3;
}

.container:hover .middle {
    opacity: 1;
}

.text {
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    padding: 16px 32px;
}
</style>

<body>
    <div class="page">
        <?php require "includes/navbar.inc.php"; //NAVBAR ?>



        <div class="content-inner">
            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">Home > <i>Store</i></h2>
                </div>
            </header>

            <?php if(!isset($_GET['buy'])) {?>
            <section class="tables">
                <?php
                    $all=0;
                    $fivem=0;
                    $websites=0;
                    ?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-9">
                            <?php

                                if (isset($_GET['c'])) {
                                    $c = $_GET['c'];
                                } else {
                                    $c = "all";
                                }

                                if (isset($_GET['page'])) {
                                    $pageno = intval($_GET['page']);
                                } else {
                                    $pageno = 1;
                                }
                                $result = $mysqli->query("SELECT * FROM SF_Scripts WHERE `public` = 1");
                                while ($row = $result->fetch_assoc()) {
                                    $all++;
                                    if ($row['key'] == "FiveM") {
                                        $fivem++;
                                    } else if ($row['key'] == "Website") {
                                        $websites++;
                                    }
                                }
                                $result->close();

                                $total_pages=1;
                                $no_of_records_per_page = 5;
                                $offset = ($pageno-1) * $no_of_records_per_page;
                                if ($c == "all") {
                                    $total_rows = $all;
                                    $total_pages = ceil($total_rows / $no_of_records_per_page);
                                    //$sql = "SELECT * FROM `SF_Scripts` WHERE `public` = 1 ORDER BY `id` DESC LIMIT $offset, $no_of_records_per_page";
                                   // $r_query = $mysqli->query($sql);

                                    $result = $mysqli->prepare("SELECT * FROM `SF_Scripts` WHERE `public` = 1 ORDER BY `id` DESC LIMIT ?, ?");
                                    $result -> bind_param('ii',$offset,$no_of_records_per_page);
                                    $result -> execute();
                                    $rez = $result -> get_result();

                                    while ($row = $rez->fetch_assoc()) {
                                            $data = json_decode($row['data'], true);
                                            if (isset($data['redirect'])) {
                                                $redirect = '<br> <button type="button" onclick="window.location=`'.$data['redirect'].'`;" class="btn btn-primary" data-dismiss="modal">WEBSITE</button>';
                                            } else {
                                                $redirect = '';
                                            }
                                            $resultt = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE `user_id` = ? ");
                                            $resultt -> bind_param('i',$row['author_id']);
                                            $resultt -> execute();
                                            $rezz = $resultt -> get_result();
                                            $author = $rezz->fetch_assoc();
                                            $resultt->close();
                                        ?>


                            <div class="card">
                                <h3 class="card-header b-b <?php echo $data["color"];?>"><i
                                        class="fa fa-file"></i>&nbsp; <?php echo $data["title"];?></h3>
                                <div class="card-body">
                                    <div class="col-sm-12">
                                        <div class="item">
                                            <div class="feed d-flex justify-content-between">
                                                <div class="feed-body d-flex justify-content-between hover_image">

                                                    <div class="container">
                                                        <img src="<?php echo $data['images'];?>" class="image">
                                                        <div class="middle">
                                                            <button type="button" onclick="alert(`IN WORKING...`)"
                                                                class="btn btn-categorycolor" data-dismiss="modal">VIEW
                                                                PHOTOS</button>
                                                        </div>
                                                    </div>

                                                    <div class="content" style="padding-left: 30px;">
                                                        <td>
                                                            <b>Id:</b> <span
                                                                style="color:white">#<?php echo $row['id'];?></span><br>

                                                            <?php if (isset($row['stock'])) {
                                                                            if ($row["stock"]==999){
                                                                                $stock="unlimited";
                                                                            } else {
                                                                                $stock=$row["stock"];
                                                                            }
                                                                            echo "<b>Stock:</b> <span>".$stock."</span><br>";
                                                                        }
                                                                    ?>
                                                            <?php if (isset($data['base'])) {echo "<b>Base:</b> <span>".$data["base"]."</span><br>";} ?>
                                                            <?php if (isset($row['key'])) {echo "<b>Game:</b> <span>".$row["key"]."</span><br>";} ?>
                                                            <?php if (isset($data['crypted'])) {echo "<b>Crypted:</b> <span>".$data["crypted"]."</span><br>";} ?>
                                                            <?php if (isset($data['description'])) {echo "<b>Description:</b> <span>".$data["description"]."</span><br>";} ?>

                                                            <b>Seller:</b><br>
                                                            <img class="img-circleeeeeeeeeeeeeeeeeeeee"
                                                                style="width: 38px;height: 38px;  border-radius: 50% !important;"
                                                                src="<?php echo $author['avatar'];?>"
                                                                alt="<?php echo $author['username'];?>"
                                                                class="img-fluid rounded-circle" />
                                                            <a
                                                                href="profile.php?user=<?php echo urlencode($author['username']);?>"><?php echo $author['username'];?></a>

                                                        </td>
                                                    </div>
                                                </div>
                                                <div class="date text-right">
                                                    <?php
                                                            if (isset($data['reduction']) && strtotime($data['reduction']['expire']) > time() ) {
                                                                echo '<font style="color:red"><b><del>'.$data['reduction']['price'].'€</del></b></font><br><b>promotion expires in:</b> '.Utils::time_elapsed_string('@'.strtotime($data['reduction']['expire']),false,true).'<br>';
                                                            }
                                                        ?>
                                                    <font style="color:#33AA33"><b><?php echo $data['price'];?>€</b>
                                                    </font><br>
                                                    <div>
                                                        <?php
                                                                if (isset($data['stars'])) {
                                                                    $stars = $data['stars'];
                                                                    $white_stars = 6-$stars;
                                                                    for ($x = 0; $x < $stars; $x++){
                                                                        echo '<i class="fa fa-star-o" style="color:#FFDB00;display: inline;" aria-hidden="true"></i>';
                                                                    }

                                                                    for ($x = 1; $x < $white_stars; $x++){
                                                                        echo '<i class="fa fa-star-o" style="color:white;display: inline;" aria-hidden="true"></i>';
                                                                    }
                                                                    echo '<br/><br/>';

                                                                }
                                                            ?>
                                                    </div>

                                                    <div style="position:absolute;bottom:0;right:20px;left:0;">
                                                        <?php
                                                                if (isset($data['redirect'])) {
                                                                    echo '
                                                                        <button type="button" onclick="window.location=`'.$data['redirect'].'`;" class="btn btn-primary" >WEBSITE</button>
                                                                    ';
                                                                }
                                                            ?>
                                                        <button type="button"
                                                            onclick="window.location=`?buy=<?php echo $row['id'];?>`;"
                                                            class="btn btn-hudcolor">BUY</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                    }
                                    $result->close();
                                } else {
                                    $param = "%$c%";
                                    $pages_res = $mysqli->prepare("SELECT * FROM `SF_Scripts` WHERE `key` LIKE ? AND `public` = 1");
                                    $pages_res -> bind_param('s',$param);
                                    $pages_res -> execute();
                                    $pages_res -> store_result();
                                    $total_rows = $pages_res->num_rows;
                                    $total_pages = ceil($total_rows / $no_of_records_per_page);
                                    $pages_res->close();

                                    $result = $mysqli->prepare("SELECT * FROM `SF_Scripts` WHERE `key` LIKE ? AND `public` = 1 ORDER BY `id` DESC LIMIT ?, ?");
                                    $result -> bind_param('sii',$param,$offset,$no_of_records_per_page);
                                    $result -> execute();
                                    $r_query = $result -> get_result();
                                    while ($row = $r_query->fetch_assoc()) {
                                            $data = json_decode($row['data'], true);

                                            if (isset($data['redirect'])) {
                                                $redirect = '<br> <button type="button" onclick="window.location=`'.$data['redirect'].'`;" class="btn btn-primary" data-dismiss="modal">WEBSITE</button>';
                                            } else {
                                                $redirect = '';
                                            }

                                            $resultt = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE `user_id` = ? ");
                                            $resultt -> bind_param('i',$row['author_id']);
                                            $resultt -> execute();
                                            $rezz = $resultt -> get_result();
                                            $author = $rezz->fetch_assoc();
                                            $resultt->close();
                                        ?>

                            <div class="card">
                                <h3 class="card-header b-b <?php echo $data["color"];?>"><i
                                        class="fa fa-file"></i>&nbsp; <?php echo $data["title"];?></h3>
                                <div class="card-body">
                                    <div class="col-sm-12">
                                        <div class="item">
                                            <div class="feed d-flex justify-content-between">
                                                <div class="feed-body d-flex justify-content-between hover_image">

                                                    <div class="container">
                                                        <img src="<?php echo $data['images'];?>" class="image">
                                                        <div class="middle">
                                                            <button type="button" onclick="alert(`IN WORKING...`)"
                                                                class="btn btn-categorycolor" data-dismiss="modal">VIEW
                                                                PHOTOS</button>
                                                        </div>
                                                    </div>

                                                    <div class="content" style="padding-left: 30px;">
                                                        <td>
                                                            <b>Id:</b> <span
                                                                style="color:white">#<?php echo $row['id'];?></span><br>

                                                            <?php if (isset($row['stock'])) {
                                                                            if ($row["stock"]==999){
                                                                                $stock="unlimited";
                                                                            } else {
                                                                                $stock=$row["stock"];
                                                                            }
                                                                            echo "<b>Stock:</b> <span>".$stock."</span><br>";
                                                                        }
                                                                    ?>
                                                            <?php if (isset($data['base'])) {echo "<b>Base:</b> <span>".$data["base"]."</span><br>";} ?>
                                                            <?php if (isset($row['key'])) {echo "<b>Game:</b> <span>".$row["key"]."</span><br>";} ?>
                                                            <?php if (isset($data['crypted'])) {echo "<b>Crypted:</b> <span>".$data["crypted"]."</span><br>";} ?>
                                                            <?php if (isset($data['description'])) {echo "<b>Description:</b> <span>".$data["description"]."</span><br>";} ?>

                                                            <b>Author:</b><br>
                                                            <img class="img-circleeeeeeeeeeeeeeeeeeeee"
                                                                style="width: 38px;height: 38px;  border-radius: 50% !important;"
                                                                src="<?php echo $author['avatar'];?>"
                                                                alt="<?php echo $author['username'];?>"
                                                                class="img-fluid rounded-circle" />
                                                            <a
                                                                href="profile.php?user=<?php echo urlencode($author['username']);?>"><?php echo $author['username'];?></a>

                                                        </td>
                                                    </div>
                                                </div>
                                                <div class="date text-right">
                                                    <?php
                                                            if (isset($data['reduction']) && strtotime($data['reduction']['expire']) > time() ) {
                                                                echo '<font style="color:red"><b><del>'.$data['reduction']['price'].'€</del></b></font><br><b>promotion expires in:</b> '.Utils::time_elapsed_string('@'.strtotime($data['reduction']['expire']),false,true).'<br>';
                                                            }
                                                        ?>
                                                    <font style="color:#33AA33"><b><?php echo $data['price'];?>€</b>
                                                    </font><br>
                                                    <div>
                                                        <?php
                                                                if (isset($data['stars'])) {
                                                                    $stars = $data['stars'];
                                                                    $white_stars = 6-$stars;
                                                                    for ($x = 0; $x < $stars; $x++){
                                                                        echo '<i class="fa fa-star-o" style="color:#FFDB00;display: inline;" aria-hidden="true"></i>';
                                                                    }

                                                                    for ($x = 1; $x < $white_stars; $x++){
                                                                        echo '<i class="fa fa-star-o" style="color:white;display: inline;" aria-hidden="true"></i>';
                                                                    }
                                                                    echo '<br/><br/>';

                                                                }
                                                            ?>
                                                    </div>

                                                    <div style="position:absolute;bottom:0;right:20px;left:0;">
                                                        <?php
                                                                if (isset($data['redirect'])) {
                                                                    echo '
                                                                        <button type="button" onclick="window.location=`'.$data['redirect'].'`;" class="btn btn-primary" >WEBSITE</button>
                                                                    ';
                                                                }
                                                            ?>
                                                        <button type="button"
                                                            onclick="window.location=`?buy=<?php echo $row['id'];?>`;"
                                                            class="btn btn-hudcolor">BUY</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                    }
                                    $result->close();
                                }
                            ?>
                            <ul class="pagination modal-3" style="float:center;">
                                <li><a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?page=".($pageno - 1)."&c=".$c; } ?>"
                                        class="prev">&laquo;</a></li>
                                <?php
                                    for ($i=1; $i <= $total_pages; $i++) {
                                        $active_page_bool = "";
                                        if ($i==$pageno) {
                                            $active_page_bool = "active";
                                        }
                                        echo '<li> <a href="?page='.$i.'&c='.$c.'" class="'.$active_page_bool.'">'.$i.'</a></li>';
                                    }
                                ?>
                                <li><a href="<?php if($pageno >= $total_pages){ echo '#';} else { echo "?page=".($pageno + 1)."&c=".$c; } ?>"
                                        class="next">&raquo;</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <h3 class="card-header b-b">
                                    <i class="fa fa-list-alt"></i>&nbsp; Categories
                                </h3>
                                <a href="?page=1&c=all" type="button"
                                    class="btn btn-categorycolor <?php if ($c=="all") echo 'active';?>">All
                                    [<?php echo $all;?>]</a>
                                <a href="?page=1&c=fivem" type="button" style="margin-top:2px;"
                                    class="btn btn-categorycolor <?php if ($c=="fivem") echo 'active';?>">FiveM
                                    [<?php echo $fivem;?>]</a>
                                <a href="?page=1&c=website" type="button" style="margin-top:2px;"
                                    class="btn btn-categorycolor <?php if ($c=="website") echo 'active';?>">Websites
                                    [<?php echo $websites;?>]</a>
                            </div>
                            <?php if (Utils::is_user_logged() && Utils::is_user_permission($user_groups['groups'],"store.edit") ) {?>
                            <div class="card">
                                <h3 class="card-header b-b">
                                    <i class="fa fa-cog"></i>&nbsp; Settings
                                </h3>
                                <a href="#" type="button" class="btn btn-success">Add</a>
                                <a href="#" type="button" class="btn btn-info" style="margin-top:2px;">Edit</a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
            </section>
            <?php } else { ?>


            <?php
                if (!Utils::is_user_logged()) {
                    header("location: ../login.php"); exit();
                }
                ?>
            <section class="tables">
                <div class="container-fluid">

                    <?php if(isset($error)) {?>
                    <div class="col-lg-14">
                        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                            role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <?php echo $error;?>
                        </div>
                    </div>
                    <?php }?>

                    <div class="row">
                        <div class="col-md-9 _left-side">
                            <div class="card">
                                <div class="card-body" style="border-radius:10px;border:2px solid var(--primary-tr);">
                                    <div class="item">
                                        <?php
                                                    $id = $_GET["buy"];

                                                    $result = $mysqli->prepare("SELECT * FROM `SF_Scripts` WHERE  `public` = 1 AND id = ?");
                                                    $result -> bind_param('i',$id);
                                                    $result -> execute();
                                                    $rez = $result -> get_result();
                                                    $cfafa = $rez->fetch_assoc();
                                                    $result->close();
                                                  //  $r_query = $mysqli->query("SELECT * FROM `SF_Scripts` WHERE  `public` = 1 AND id = '$id'");
                                                   // $cfafa = $r_query->fetch_assoc();
                                                    //$r_query->close();
                                                    if ($cfafa) {
                                                        $data_cfafa = json_decode($cfafa['data'], true);

                                                        $yy = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE `user_id` = ?");
                                                        $yy -> bind_param('i',$cfafa['author_id']);
                                                        $yy -> execute();
                                                        $rezz = $yy -> get_result();
                                                        $author_cfafa = $rezz->fetch_assoc();
                                                        $yy->close();

                                                      //  $yy = $mysqli->query("SELECT * FROM `SF_Users` WHERE `user_id` = '".$cfafa['author_id']."' ");
                                                      //  $author_cfafa = $yy->fetch_assoc();
                                                       // $yy->close();
                                                ?>
                                        <div class="feed d-flex justify-content-between">
                                            <div class="feed-body d-flex justify-content-between hover_image">
                                                <div class="content" style="padding-left: 30px;">


                                                    <b>Id:</b> <span
                                                        style="color:white">#<?php echo $cfafa['id'];?></span><br>
                                                    <?php
                                                                        if (isset($data_cfafa['stars'])) {
                                                                            $stars = $data_cfafa['stars'];
                                                                            $white_stars = 6-$stars;
                                                                            echo '<b>Rating:</b> ';
                                                                            for ($x = 0; $x < $stars; $x++){
                                                                                echo '<i class="fa fa-star-o" style="color:#FFDB00;display: inline;" aria-hidden="true"></i>';
                                                                            }

                                                                            for ($x = 1; $x < $white_stars; $x++){
                                                                                echo '<i class="fa fa-star-o" style="color:white;display: inline;" aria-hidden="true"></i>';
                                                                            }
                                                                            echo '<br/>';

                                                                        }
                                                                    ?>
                                                    <?php
                                                                        if (isset($cfafa['stock'])) {
                                                                            if ($cfafa["stock"]==999){
                                                                                $stock="unlimited";
                                                                            } else {
                                                                                $stock=$cfafa["stock"];
                                                                            }
                                                                            echo "<b>Stock:</b> <span>".$stock."</span><br>";
                                                                        }
                                                                    ?>
                                                    <?php if (isset($data_cfafa['base'])) {echo "<b>Base:</b> <span>".$data_cfafa["base"]."</span><br>";} ?>
                                                    <?php if (isset($cfafa['key'])) {echo "<b>Game:</b> <span>".$cfafa["key"]."</span><br>";} ?>
                                                    <?php if (isset($data_cfafa['crypted'])) {echo "<b>Crypted:</b> <span>".$data_cfafa["crypted"]."</span><br>";} ?>
                                                    <?php if (isset($data_cfafa['description'])) {echo "<b>Description:</b> <span>".$data_cfafa["description"]."</span><br>";} ?>

                                                    <b>Author:</b><br>
                                                    <img class="img-circleeeeeeeeeeeeeeeeeeeee"
                                                        style="width: 38px;height: 38px;  border-radius: 50% !important;"
                                                        src="<?php echo $author_cfafa['avatar'];?>"
                                                        alt="<?php echo $author_cfafa['username'];?>"
                                                        class="img-fluid rounded-circle" />
                                                    <a
                                                        href="profile.php?user=<?php echo urlencode($author_cfafa['username']);?>"><?php echo $author_cfafa['username'];?></a>
                                                </div>
                                                <div class="container">
                                                    <img src="<?php echo $data_cfafa['images'];?>" class="image">
                                                    <div class="middle">
                                                        <button type="button" onclick="alert(`IN WORKING...`)"
                                                            class="btn btn-categorycolor" data-dismiss="modal">VIEW
                                                            PHOTOS</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="date text-right">
                                                <?php
                                                                    if (isset($data_cfafa['reduction'])) {
                                                                        echo '<font style="color:red"><b><del>'.$data_cfafa['reduction']['price'].'€</del></b></font><br><b>promotion expires in:</b> '.Utils::time_elapsed_string('@'.strtotime($data_cfafa['reduction']['expire']),false,true).'<br>';
                                                                    }
                                                                ?>
                                                <font style="color:#33AA33">
                                                    <b><?php $subtotal = $data_cfafa['price']; echo $data_cfafa['price'];?>€</b>
                                                </font><br>
                                            </div>
                                        </div>
                                        <?php
                                                    } else {
                                                        header("location: store.php"); exit();
                                                    }
                                                ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                        <div class="col-md-3 _right-side">
                            <div class="card">
                                <div class="daily-feeds card">
                                    <div class="card-header bg-violet">
                                        <h3 class="h4"><i class="fa fa-shopping-basket" style="color:white"></i>
                                            Finalize Order</h3>
                                    </div>
                                    <div class="table-responsive">
                                        <?php
                                                    $tva = round(($subtotal/100)*2,2);
                                                    $cpn = 0;

                                                    if (isset($_POST['coupon'])) {
                                                        setcookie("coupon_code",$_POST['coupon'], time() + (3600*5));
                                                        echo '<META HTTP-EQUIV="refresh" CONTENT="0">';
                                                    }
                                                ?>
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td><b>Subtotal</b></td>
                                                    <td><b><?php echo $subtotal; ?>&euro;</b></td>
                                                </tr>
                                            </tbody>
                                            <tbody>
                                                <tr>
                                                    <td><b>TVA (2%)</b></td>
                                                    <td><b><?php echo $tva;?>&euro;</b></td>
                                                </tr>
                                            </tbody>
                                            <?php
                                                        if (GLOBAL_PROMOTION > 0) {
                                                            $subtotal = $subtotal - round(($subtotal/100)*GLOBAL_PROMOTION,2);
                                                            echo '<tbody>
                                                                    <tr>
                                                                        <td><b>Promotion (-'.GLOBAL_PROMOTION.'%)</b></td>
                                                                        <td><b>-'.round(($subtotal/100)*GLOBAL_PROMOTION,2).'&euro;</b></td>
                                                                    </tr>
                                                                </tbody>';
                                                        }
                                                    ?>
                                            <?php
                                                        if (isset($_COOKIE['coupon_code'])) {
                                                            $cupon_db = explode("_",$_COOKIE['coupon_code']);
                                                            if (count($cupon_db) == 2) {

                                                                $param = "%$cupon_db[0]%";
                                                                $result = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE username LIKE ?");
                                                                $result -> bind_param('s',$param);
                                                                $result -> execute();
                                                                $rez = $result -> get_result();
                                                                $coupon_details = $rez->fetch_assoc();

                                                             //  $r_query = $mysqli->query("SELECT * FROM `SF_Users` WHERE username LIKE '%$cupon_db[0]%'");
                                                              //  $coupon_details = $r_query->fetch_assoc();
                                                                if ($coupon_details) {
                                                                    $discount_mysql = json_decode($coupon_details['referral'],true);
                                                                    $is_good_coupon=true;
                                                                    if (isset($discount_mysql['code'][$cupon_db[1]])) {
                                                                        $cpn = round((($subtotal+$tva)/100)*$discount_mysql['code'][$cupon_db[1]]['value'],2);
                                                                        echo '
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><b>Coupon (-'.$discount_mysql['code'][$cupon_db[1]]['value'].'%)</b></td>
                                                                                <td><b>-'.$cpn.'&euro;</b></td>
                                                                            </tr>
                                                                        </tbody>';
                                                                    }
                                                                }
                                                                $result->close();
                                                            }
                                                        }
                                                        $total = round(($subtotal+$tva)-$cpn,2);
                                                    ?>
                                            <tbody>
                                                <tr>
                                                    <td><b>TOTAL</b></td>
                                                    <td><b><?php echo $total;?>&euro;</b></td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>

                                </div>

                                <script>
                                function arata_a(bool) {
                                    document.getElementById("buy_alert").style.display = bool;
                                }
                                </script>

                                <div style="margin-top:-40px;"
                                    class="d-flex flex-sm-row flex-column justify-content-end">
                                    <button onclick="arata_a('block');" style="margin-top:10px;"
                                        class="btn btn-success btn-block glow mr-sm-1 mb-1">Proceed to checkout</button>
                                </div>
                                <div id="buy_alert" style="display:none;">
                                    <form method="post">
                                        <div class="d-flex flex-sm-row flex-column justify-content-end"
                                            style="margin-top:-8px;">
                                            <button type="submit" name="cumpara" value="<?php echo $cfafa['id'];?>"
                                                style="margin-top:10px;"
                                                class="btn btn-success btn-block glow mr-sm-1 mb-1">Buy</button>
                                            <button onclick="arata_a('none');" style="margin-top:10px;"
                                                class="btn btn-danger btn-block glow mr-sm-1 mb-1">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php

                                        if (isset($_POST['cumpara'])) {
                                            $idd = $_POST['cumpara'];

                                            $result = $mysqli->prepare("SELECT * FROM `SF_Scripts` WHERE `public` = 1 AND id = ?");
                                            $result -> bind_param('i',$idd);
                                            $result -> execute();
                                            $rez = $result -> get_result();
                                            $verifica_id = $rez->fetch_assoc();

                                          // $x = $mysqli->query("SELECT * FROM `SF_Scripts` WHERE `public` = 1 AND id = '$idd'");
                                           // $verifica_id = $x->fetch_assoc();

                                            if ($verifica_id) {
                                                $verifica_data = json_decode($verifica_id['data'], true);

                                                $resultt = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE `user_id` = ? ");
                                                $resultt -> bind_param('i',$verifica_id['author_id']);
                                                $resultt -> execute();
                                                $rezz = $resultt -> get_result();
                                                $verifica_author = $rezz->fetch_assoc();
                                                $resultt->close();
                                                if ($verifica_author) {
                                                    if ($verifica_author['balance']>=$total) {
                                                        if ($total > 0) {
                                                            $query = $mysqli->prepare("UPDATE `SF_Users` SET `balance` = `balance` - ? WHERE `user_id` = ? ");
                                                            $query -> bind_param('si',$total,$verifica_id['author_id']);
                                                            $query -> execute();
                                                            $query -> close();
                                                        }

                                                        $query = $mysqli->prepare("UPDATE `SF_Scripts` SET `sells` = `sells` + 1 WHERE `id` = ? ");
                                                        $query -> bind_param('i',$idd);
                                                        $query -> execute();
                                                        $query -> close();

                                                     //   $mysqli->query("UPDATE `SF_Scripts` SET `sells` = `sells` + 1 WHERE `id` = '".$idd."' ");
                                                     $utils->add_log($verifica_author['username'],"Has bought successfully! (<a href='store.php?buy=$idd'>".$verifica_data['title']."</a>)");

                                                        if (isset($_COOKIE['coupon_code'])) {
                                                            $cupon_db = explode("_",$_COOKIE['coupon_code']);
                                                            if (count($cupon_db) == 2) {
                                                                $param1="%$cupon_db[0]%";
                                                                $r_query = $mysqli->prepare("SELECT * FROM `SF_Users` WHERE username LIKE ?");
                                                                $r_query -> bind_param('s',$param1);
                                                                $r_query -> execute();
                                                                $rezzz = $r_query -> get_result();
                                                                $coupon_details = $rezzz->fetch_assoc();

                                                                if ($coupon_details) {
                                                                    $discount_mysql = json_decode($coupon_details['referral'],true);
                                                                    if (isset($discount_mysql['code'][$cupon_db[1]])) {
                                                                        $discount_mysql['code'][$cupon_db[1]]['uses']++;

                                                                        $query = $mysqli->prepare("UPDATE SF_Users SET referral = ? WHERE username LIKE ?");
                                                                        $param2=json_encode($discount_mysql);
                                                                        $query -> bind_param('ss',$param2,$param1);
                                                                        $query -> execute();
                                                                        $query -> close();
                                                                    }
                                                                }
                                                                $r_query->close();
                                                            }
                                                        }

                                                        //trimite script etccc;
                                                        echo '<META HTTP-EQUIV="refresh" CONTENT="1">';
                                                    } else {
                                                        //error no money;
                                                    }
                                                }
                                            } else {
                                                header("location: store.php");
                                            }
                                            $result->close();
                                        }
                                    ?>
                            <div class="card">
                                <form method="post">
                                    <div class="daily-feeds card">
                                        <div class="card-header">
                                            <h3 class="h4"><i class="fa fa-gift" style="color:white"></i> Redeem Coupon
                                            </h3>
                                        </div>
                                        <br>
                                        <div class="form-group col-lg-12">
                                            <input required type="text" placeholder="Enter coupon" class="form-control"
                                                value="<?php if (isset($is_good_coupon)){ echo $_COOKIE['coupon_code'];} ?>"
                                                name="coupon" />
                                        </div>
                                    </div>
                                    <div style="margin-top:-40px;"
                                        class="d-flex flex-sm-row flex-column justify-content-end">
                                        <button type="submit" style="margin-top:10px;"
                                            class="btn btn-primary btn-block glow mr-sm-1 mb-1">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Column -->
                        <!-- Column -->
                    </div>
                </div>
            </section>
            <?php } require "includes/footer.inc.php"; //FOOTER ?>
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