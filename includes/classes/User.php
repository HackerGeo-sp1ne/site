<?php

spl_autoload_register(function ($class_name) {
    include $class_name.".php";
});


class User extends DataBase{
    public function __construct(){
        parent::__construct();
    }
    private $_errors = array();
    public function try_login($items){
        $validate = new Validate();
        $validate->check($items, array(
//            'username' => array('required' => true),
//            'password' => array('required' => true),

            'login_key' => array('required' => true)
        ));
        $login_key = trim($items['login_key']);
//        $username = trim($items['username']);
//        $password = $items['password'];
        
        if($validate->passed()) {
            $result = $this->connection->prepare("SELECT * FROM `SF_Users` WHERE `login_key` = ?");
//            $result = $this->connection->prepare("SELECT * FROM `SF_Users` WHERE `username` = ?");
            //$result -> bind_param('s',$username);
            $result -> bind_param('s',$login_key);
            $result -> execute();
            $rez = $result -> get_result();
            $row = $rez->fetch_assoc();

       //     if ($row && password_verify($password, $row['password'])) {
            if ($row) {
                if ($row['verified']=="yes") {
                    if (json_decode($row['banned'],true)['banned']) {
                        $this->addError('Your account is banned!');
                    } else {
                        Session::put('logged', true);
                        Session::put('user', $row['username']);
                        Session::put('id', $row['user_id']);

                        if(!empty($_POST["rememberme"])) {
//                            Cookie::put("login_user", $row['username'], 3600*24*10);
//                            Cookie::put("login_password", $password, 3600*24*10);
                            Cookie::put("login_key", $row['login_key'], 3600*24*10);
                        } else {
//                            Cookie::delete("login_user");
////                            Cookie::delete("login_password");
                            Cookie::delete("login_key");
                        }
                        $this->update_lastlogin($row['username'],Utils::get_ip());

                        global $utils;
                        $utils->add_log($row['username'],"Successfully logged!");
                        header("location: index.php"); exit();
                    }
                } else {
                    $this->addError('Please verify the E-mail!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <form method="POST" action="'.$_SERVER["PHP_SELF"].'"><input type="hidden" name="send_email_usr" value="'.$row['username'].'"><input type="submit" name="send_email" value="Click here to send again"/></form>');
                }
//            } else {
//                $this->addError("Incorrect password or name!");
//            }
              } else {
                  $this->addError("Incorrect key!");
              }
        } else {
            foreach($validate->errors() as $error) {
                $this->addError($error);
            }
        }        
    }
    public function verify_email($code) {
        $result = $this->connection->prepare("SELECT * FROM `SF_Users` WHERE verified = ?");
        $result -> bind_param('s',$code);
        $result -> execute();
        $rez = $result -> get_result();
        $row = $rez->fetch_assoc();
        if ($row) {
            $query = $this->connection->prepare("UPDATE `SF_Users` SET verified = 'yes' WHERE username = ?");
            $query -> bind_param('s',$row['username']);
            $query -> execute();
            $query -> close();
        }
        $result->close();
    }
    public function update_lastlogin($user,$ip) {
        $date = date("d-m-Y H:i:s");
        $query = $this->connection->prepare("UPDATE SF_Users SET last_login=?, ip=? WHERE username = ?");
        $query -> bind_param('sss',$date,$ip,$user);
        $query -> execute();
        $query -> close();
    }

    public function try_register($items){
        $validate = new Validate();
        $validate->check($items, array(
            'username' => array('required' => true,'is_username' => true,'min' => 4,'max' => 20),
            'email' => array('required' => true,'is_email' => true),
            'password' => array('required' => true,'min' => 5,'max' => 30),
            'password2' => array('password2' => true,'matches' => 'password')
        ));

        $username = trim($items['username']);
        $email = $items['email'];
        $password = $items['password'];
        $password2 = $items['password2'];
        
        if($validate->passed()) {
            $result = $this->connection->prepare("SELECT * FROM `SF_Users` WHERE `username` = ? or `email` = ?");
            $result -> bind_param('ss',$username,$email);
            $result -> execute();
            $rez = $result -> get_result();
            $row = $rez->fetch_assoc();
            if ($row) {
                $this->addError("Username or Email already exists, try another.");
            } else {
                $date = date("d-m-Y h:i");
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $verify_code = Utils::generateString(30);

                //email sender IMPORTANT
                $mesaj_email = '<style>.butonas{border: none; border-radius: 15px; font-family:Poppins, sans-serif; border: 3px solid #41ace0; text-decoration: none; background-color: #41ace0; padding: 0.5rem 1rem; color: white;}.butonas:hover{transition: all 0.5s ease-in-out; background-color: #41ace060; color: white; border-radius: 20px;}</style> <td> <table width="600" cellspacing="0" cellpadding="0" style="background-color: #2F333E;border:1px solid #28292F;border-radius:3px;margin:10px;margin-top:30px;"> <tbody> <tr> <td align="center" style="overflow:hidden;border-top-left-radius:3px;border-top-right-radius:3px;"> <img src="https://i.imgur.com/pOGKQGt.png" alt="StayFrosty" width="600" height="170"> </td></tr><tr> <td style="font-size:1px;line-height:30px;" height="30">&nbsp;</td></tr><tr> <td style="font-family:Poppins, sans-serify;font-size:28px;font-weight:bold;text-align:center;color:white;">E-mail Confirmation<br></td></tr><tr> <td style="font-size:1px;line-height:30px;" height="30">&nbsp;</td></tr><tr> <td style="font-family:sans-serif;font-size:15px;color:#C2C2CC;line-height:24px;text-align:center;">Hey <b>.'.$username.'.</b>,<span><p>It looks like you just signed up for <b>StayFrosty</b>, that’s awesome!<br>Can we ask you for email confirmation?<br><b>Just click the button bellow.</b></p></span></td></tr><tr> <td style="font-size:1px;line-height:40px;" height="40">&nbsp;</td></tr><tr> <td align="center"> <a rel="noreferrer" target="_blank" href="'.SITE_URL."/index.php?verify_email=".$verify_code.'" class="butonas">CONFIRM EMAIL ADRESS</a> </td></tr><tr> <td style="font-size:1px;line-height:50px;" height="50">&nbsp;</td></tr></tbody> </table> </td>';
                $emailsend = new Email();
                if ($emailsend->send_Email($email,"support@hackergeo.com",$mesaj_email,$username)) {
                    $r_query = $this->connection->prepare("INSERT IGNORE INTO SF_Users(`username`, `password`, `first_login` ,`email`, `ip`,`verified`) VALUES(?,?,?,?,?,?)");
                    $r_query -> bind_param('ssssss',$username,$hashedPassword,$date,$email,Utils::get_ip(),$verify_code);
                    $r_query -> execute();
                    if ($r_query) {
                        if (isset($_GET['r'])){
                            $refferal = new Referral();
                            $refferal->exists($_GET['r'],"signups");
                        }
                        header("location: ../login.php"); exit();
                    } else {
                        $this->addError('Something went wrong.');
                    }
                    $r_query -> close();
                } else {
                    $this->addError('Email Sender not working, try again later...');
                }
            }
          } else {
            foreach($validate->errors() as $error) {
                $this->addError($error);
            }
        }        
    }

    public function change_password($items){
        $validate = new Validate();
        $validate->check($items, array(
            'username' => array('required' => true,'is_username' => true,'min' => 4,'max' => 20),
            'actual_password' => array('required' => true),
            'new_password' => array('new_password' => true,'min' => 5,'max' => 30),
            'new_password2' => array('new_password2' => true,'matches' => 'new_password')
        ));

        $username = trim($items['username']);
        $actual_password = $items['actual_password'];
        $new_password = $items['new_password'];
        $new_password2 = $items['new_password2'];
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        if($validate->passed()) {
            $result = $this->connection->prepare("SELECT `password` FROM `SF_Users` WHERE `username` = ?");
            $result -> bind_param('s',$username);
            $result -> execute();
            $rez = $result -> get_result();
            $row = $rez->fetch_assoc();
            if ($row && password_verify($row['password'], $actual_password)) {
                $query = $this->connection->prepare("UPDATE SF_Users SET password=? WHERE username = ?");
                $query -> bind_param('ss',$hashedPassword,$username);
                $query -> execute();
                $query -> close();
            } else {
                $this->addError("The password does not match the one in the database!");
            }
        } else {
            foreach($validate->errors() as $error) {
                $this->addError($error);
            }
        }      
    }
    public function add_balance(string $user,string $key){
        $result = $this->connection->prepare("SELECT * FROM `SF_BalanceKeys` WHERE `key` = ?");
        $result -> bind_param('s',$key);
        $result -> execute();
        $rez = $result -> get_result();
        $row = $rez->fetch_assoc();
        if ($row) {
            $query = $this->connection->prepare("UPDATE SF_Users SET balance=balance+? WHERE username = ?");
            $query -> bind_param('is',$row['balance'],$user);
            $query -> execute();
            $query -> close();

            $query = $this->connection->prepare("DELETE FROM SF_BalanceKeys WHERE `key` = ?");
            $query -> bind_param('s',$key);
            $query -> execute();
            $query -> close();

            header("Location: profile.php");
            //    echo '<meta http-equiv = "refresh" content = "1; url = profile.php" />';
        } else {
            echo "
                    <div class='alert alert-danger alert-dismissible bg-danger text-white border-0 fade show' role='alert'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                        </button>
                        Key Doesn't exists!
                    </div>
                ";
        }
        $result->close();
    }

    public function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }

}
