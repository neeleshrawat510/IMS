<?php

$fp = @fsockopen("smtp-relay.brevo.com", 587, $errno, $errstr, 10);

if (!$fp) {
    echo "Connection failed<br>";
    echo "Error: $errno<br>";
    echo "Message: $errstr";
} else {
    echo "SMTP connection successful!";
    fclose($fp);
}