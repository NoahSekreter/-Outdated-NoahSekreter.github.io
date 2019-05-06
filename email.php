<?php
if(isset($_POST['submit'])){
    email();
}
function email(){
    if(isset($_POST['name'], $_POST['email'], $_POST['message']) && $_POST['g-recaptcha-response'] != null) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        $to = "nsekreter@gmail.com";
        $email_subject = "$name sent a message from your site";
        $email_body = "E-mail: $email\r\n" .
            "$message\r\n";
        $headers = "From:” . $name . ”\r\n" .
            "Reply-To: nsekreter@gmail.com\r\n" .
            "X-Mailer: PHP/" . phpversion();
        if(isValid()) {
            $mail = mail($to,$email_subject,$email_body,$headers) ? "Mail sent. Thank you for your time!" : "Mailing failed";
            echo "<script type='text/javascript'>alert('$mail'); window.location.href='index.php';</script>";
        }
    }
    else {
        echo "<script type='text/javascript'>alert('Failed to send mail'); window.location.href='index.php';</script>";
    }
}

function isValid() {
    try {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret'   => 'INPUT SOMETHING HERE',
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR']];
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result)->success;
    }
    catch (Exception $e) {
        return null;
    }
}
