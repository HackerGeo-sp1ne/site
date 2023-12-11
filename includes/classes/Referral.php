<?php

spl_autoload_register(function ($class_name) {
    include $class_name.".php";
});

class Referral extends DataBase {
    private $username;
    private $refferals = array();

    public function __construct($user=""){
        parent::__construct();
        if ($user!="") {
            $this->username = $user;
            $result = $this->connection->prepare("SELECT * FROM `SF_Users` WHERE `username` = ?");
            $result->bind_param('s', $user);
            $result->execute();
            $rez = $result->get_result();
            $user_data = $rez->fetch_assoc();
            if ($user_data) {
                $this->refferals = json_decode($user_data['referral'], true);
            }
        }
    }

    public function create($discount) {
        if ( count($this->refferals['code'])<2 && ($discount == 10 || $discount == 15) ) {
            $generated_code = Utils::generateString(7);
            $this->refferals['code'][$generated_code] = array('value'=>$discount, 'uses'=>0);
            $ref_encoded = json_encode($this->refferals);
            $utils = new Utils();
            $utils->add_log($this->username,"Generated a discount code.");
            $query = $this->connection->prepare("UPDATE SF_Users SET referral=? WHERE username = ?");
            $query -> bind_param('ss',$ref_encoded,$this->username);
            $query -> execute();
            $query -> close();
        }
    }
    public function delete(string $code) {
        unset($this->refferals['code'][$code]);
        $query = $this->connection->prepare("UPDATE SF_Users SET referral=? WHERE username = ?");
        $query -> bind_param('ss',json_encode($this->refferals),$this->username);
        $query -> execute();
        $query -> close();
    }
    public function get_url(string $name)
    {
        return $this->refferals['url'][$name];
    }
    public function get_referrals()
    {
        return $this->refferals['code'];
    }

    public function exists($name,$method) {
        $result = $this->connection->prepare("SELECT * FROM `SF_Users` WHERE `username` = ?");
        $result -> bind_param('s',$name);
        $result -> execute();
        $rez = $result -> get_result();
        $row = $rez->fetch_assoc();
        if ($row) {
            $this->refferals = json_decode($row['referral'],true);
            $this->refferals['url'][$method]++;

            $query = $this->connection->prepare("UPDATE SF_Users SET referral=? WHERE username = ?");
            $query -> bind_param('ss',json_encode($this->refferals),$name);
            $query -> execute();
            $query -> close();
        }
        $result->close();
    }
}