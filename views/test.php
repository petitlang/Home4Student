<?php
$to = "mouzhengli2002@gmail.com";
$subject = "Test Email from PHP";
$message = "Your verification code is" . rand(100000,999999);
$headers = "From: L2164753957@gmail.com";

if(mail($to, $subject, $message, $headers)) {
    echo "send mail success!";
} else {
    echo "send mail failed!";
}
?>
