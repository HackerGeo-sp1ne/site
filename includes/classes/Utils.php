<?php

spl_autoload_register(function ($class_name) {
    include $class_name.".php";
});

class Utils extends DataBase {
    public function __construct(){
        parent::__construct();
    }
    public static function get_ip() {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip =getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip =getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip =getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_VIA')) {
            $ip = getenv('HTTP_VIA');
        } elseif (getenv('HTTP_USERAGENT_VIA')) {
            $ip = getenv('HTTP_USERAGENT_VIA');
        } elseif (getenv('HTTP_X_CLUSTER_CLIENT_IP')) {
            $ip =getenv('HTTP_X_CLUSTER_CLIENT_IP');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip =getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } elseif (getenv('HTTP_PROXY_CONNECTION')) {
            $ip = getenv('HTTP_PROXY_CONNECTION');
        } elseif (getenv('HTTP_XPROXY_CONNECTION')) {
            $ip = getenv('HTTP_XPROXY_CONNECTION');
        } elseif (getenv('HTTP_PC_REMOTE_ADDR')) {
            $ip = getenv('HTTP_PC_REMOTE_ADDR');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function get_groups() {
        global $groups_sets;
        return $groups_sets;
    }
    
    public static function is_user_permission($groups,$permission) {
        global $groups_sets;
        if (isset($groups) && isset($permission)) {
            $grs = $groups_sets;
            //$grs = $this->get_groups();
            foreach($groups as $gr_name => $gr_bool){
                if ($gr_bool) {
                    if (isset($grs[$gr_name])) {
                        foreach ($grs[$gr_name]['permissions'] as $value) {
                            if ($value == $permission) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
    public static function get_groups_design($groups,$prefix = '',$permission = '') {
        $de="";
        global $groups_sets;
        if (isset($groups)) {
            $grs = $groups_sets;
           // $grs = $this->get_groups();
            foreach($groups as $gr_name => $gr_bool){
                if ($gr_bool) {
                    if (isset($grs[$gr_name])) {
                        if (isset($grs[$gr_name]['permissions'])) {
                            foreach ($grs[$gr_name]['permissions'] as $value) {
                                if ($value == $permission) {
                                    $de = $de . '&nbsp;<span style="background-color: '.$grs[$gr_name]['color'].' !important;" class="badge bg-violet""><i class="'.$grs[$gr_name]['icon'].'"></i> '.$grs[$gr_name]['name'].'</span>'.$prefix;
                                }
                            }
                        }
                    }
                }
            }
        }
        $de = $de . '&nbsp;<span style="background-color: yellow !important;color:black;" class="badge bg-violet""><i class="fa fa-user"></i> User</span>'.$prefix;
        return $de;
    }
    public static function time_elapsed_string($datetime, $full = false,$x=false) {
        $now = new DateTime;
        $ago = new DateTime( $datetime );
        $diff = (array) $now->diff( $ago );

        $diff['w']  = floor( $diff['d'] / 7 );
        $diff['d'] -= $diff['w'] * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {
            if ( $diff[$k] ) {
                $v = $diff[$k] . ' ' . $v .( $diff[$k] > 1 ? 's' : '' );
            }
            else {
                unset( $string[$k] );
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        if ($x) {
            return implode(', ', $string);
        } else {
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }
    }

    public static function balanceFormat($int){
        return number_format((float)$int,1,",",".");
    }

    public static function generateString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function is_online($last_online) {
        if ( (time()-strtotime($last_online) < LAST_ONLINE_CHECK) ) {
            return true;
        }
        return false;
    }
    
    public static function add_problem($desc,$priority) {
        $file = file_get_contents("./template/problems.json");
        if ($file) {
            $file_data = json_decode($file, true);
            $file_data['errors'][] = ['time' => date("h:i:s d-m-Y"), 'priority' => $priority, 'problem' => $desc];
            file_put_contents("./template/problems.json",json_encode($file_data,true));
        }
    }
    public function add_log($author,$log) {
        $date = date("d-m-Y H:i:s");
        $query = $this->connection->prepare("INSERT IGNORE INTO SF_Logs(`author`, `log`, `posted`) VALUES (?,?,?)");
        if($query){
        } else {
            var_dump($this->connection->error);
        }
        $query -> bind_param('sss',$author,$log,$date);
        $query -> execute();
        $query -> close();
    }
    public static function is_user_logged() {
        if (Session::exists("logged")) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function get_likes($user_id) {
        $likes = [];
        if (isset($user_id)) {
            $result = $this->connection->prepare("SELECT * FROM `SF_Likes` WHERE `to` = ?") or die($this->connection->error.__LINE__);
            if($result){

            } else {
            var_dump($this->connection->error);
                
            }
            $result -> bind_param('i',$user_id);
            $result -> execute();
            $rez = $result -> get_result();
            while ($row = $rez->fetch_assoc()) {
                array_push($likes,$row);
            }
            $result->close();
        }
        return $likes;
    }
    public function get_is_liked($from_user,$to_user) {
        if (isset($from_user) && isset($to_user)) {
            $result = $this->connection->prepare("SELECT * FROM `SF_Likes` WHERE `to` = ? AND `from` = ? ");
            $result -> bind_param('ii',$to_user,$from_user);
            $result -> execute();
            $rez = $result -> get_result();
            $row = $rez->fetch_assoc();
            if ($row) {
                return 1;
            }
            $result->close();
        }
        return 0;
    }
}
?>