<?php

return [
    'db' => require(__DIR__ . '/db.php'),
    'user' => [
        'identityClass' => false,
        'enableAutoLogin' => true
    ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => true,
    ],
    'assetManager' => [
        'forceCopy' => true
    ],
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
        ],
    ],
    'request' => [
        'cookieValidationKey' => 'RO10EEu9rvKLlwrH4i92vTkY8OjNAZsm',
    ],
];