<?php

file_put_contents(__DIR__ . '/onpay.log', date('Y-m-d H:i:s') . "\n", FILE_APPEND);
ob_start();
var_dump($_POST);
file_put_contents(__DIR__ . '/onpay.log', ob_get_clean(), FILE_APPEND);