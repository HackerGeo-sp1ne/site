<?php 
require("PHPmailer/class.phpmailer.php");

class Email {
    private $host = 'hackergeo.com';
    private $username = 'support@hackergeo.com';
    private $password = 'Parola!noua123';
 
    private $_sended = false;
    private function send_Email($to,$subject,$message,$name) {
        $mail             = new PHPMailer();
        $body             = $message;
        $mail->IsSMTP();
        $mail->Host       = $this->host;                  
        $mail->SMTPAuth   = true;
        $mail->Host       = $this->host;
        $mail->Port       = 587;
        $mail->Username   = $this->username;
        $mail->Password   = $this->password;
        $mail->SMTPSecure = 'tls';
        $mail->SetFrom($this->username, $this->username);
        $mail->AddReplyTo($this->username,$this->username);
        $mail->Subject    = $subject;
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
        $mail->MsgHTML($body);
        $address = $to;
        $mail->AddAddress($address, $name);
        if(!$mail->Send()) {
            $this->_sended=false;
            return 0;
        } else {
            $this->_sended=true;
            return 1;
        }
    }
    private function is_Sended() {
        return $this->_sended;
    }
}

?>