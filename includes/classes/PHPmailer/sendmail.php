<?php
    require_once('class.phpmailer.php');
    function sendmail($to,$subject,$message,$name)
    {
        $mail             = new PHPMailer();
        $body             = $message;
        $mail->IsSMTP();
        $mail->Host       = "hackergeo.com";                  
        $mail->SMTPAuth   = true;
        $mail->Host       = "hackergeo.com";
        $mail->Port       = 587;
        $mail->Username   = "support@hackergeo.com";
        $mail->Password   = "Parola!noua123";
        $mail->SMTPSecure = 'tls';
        $mail->SetFrom('support@hackergeo.com', 'support@hackergeo.com');
        $mail->AddReplyTo("support@hackergeo.com","support@hackergeo.com");
        $mail->Subject    = $subject;
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
        $mail->MsgHTML($body);
        $address = $to;
        $mail->AddAddress($address, $name);
        if(!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }
    }

?>