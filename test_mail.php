<?php

echo "<pre>";

echo "HOST: ";
var_dump(getenv("SMTP_HOST"));

echo "USER: ";
var_dump(getenv("SMTP_USER"));

echo "PASS: ";
var_dump(getenv("SMTP_PASS"));

echo "PORT: ";
var_dump(getenv("SMTP_PORT"));

echo "FROM: ";
var_dump(getenv("MAIL_FROM"));

echo "APP_URL: ";
var_dump(getenv("APP_URL"));