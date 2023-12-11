<?php

spl_autoload_register(function ($class_name) {
    include $class_name.".php";
});

class Chat extends DataBase
{
    public function __construct(){
        parent::__construct();
    }
    public function GetMessages() {
        $r_query = $this->connection->query("(SELECT * FROM `SF_Chats` ORDER BY `id` DESC LIMIT 30) ORDER BY id ASC");
        while ($row = $r_query->fetch_assoc()) {
            $r_queryy = $this->connection->query("SELECT * FROM `SF_Users` WHERE `user_id` = '".$row['author_id']."'");
            $roww = $r_queryy->fetch_assoc();
            if ($roww) {
                echo '
                <li class="media">
                    <img alt="image" class="img-circleeeeeeeeeeeeeeeeeeeee" style="width: 38px;height: 38px;  border-radius: 50% !important;" src="'.$roww["avatar"].'" />
                    <div class="media-body">
                        <h5 class="mt-0 mb-1 mt-2" style="max-width:680px;">
                            <b><a href="profile.php?user='.$roww['username'].'">&nbsp; <font class="font-weight-bolder"><u>'.$roww['username'].'</u></font></a></b>: <font class="font-weight-lighter">'.$row['text'].'</font>
                        </h5>
                        <small class="text-muted">
                            <span data-placement="bottom" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="'.$row['posted'].'"><i class="fa fa-clock-o"></i> '.Utils::time_elapsed_string('@'.strtotime($row['posted'])).'</span>
                        </small>
                    </div>
                </li>';
            }
            $r_queryy->close();
        }
        $r_query->close();
    }
}

$chat = new Chat();
$chat->GetMessages();