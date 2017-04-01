<?php

return [
    'db' => require(__DIR__ . '/db.php'),
    'user' => [
        'class' => 'app\components\User',
        'identityClass' => 'app\models\User',
        'enableAutoLogin' => false,
        'authTimeout' => 600
    ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => false,
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'diamondrewards8@gmail.com', //xxxx@gmail.com
            'password' => 'XYZdiamond_',
            'port' => '587',
            'encryption' => 'tls',
        ],
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
    'log' => [
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'categories' => ['application'],
                'logVars' => []
            ],
            [
                'class' => 'yii\log\FileTarget',
                'categories' => ['cron'],
                'logVars' => [],
		'logFile' => '@runtime/logs/cron.log'
            ]
        ]
    ],
    'perfectMoney' => [
        'class' => 'yiidreamteam\perfectmoney\Api',
        'accountId' => '4860608',
        'accountPassword' => 'QAZxcvbnm1',
        'walletNumber' => 'U13851939',
        'merchantName' => 'adams',
        'alternateSecret' => '6Z85QC6L4HAbmyFkWHStyIrBP',

        //'accountId' => '5816901',
        //'accountPassword' => 'qwerty123',
        //'walletNumber' => 'U13860909',
        //'merchantName' => 'GeorgeLemish',
        //'alternateSecret' => '1H3f88y7FTZPLoPXdImityMEU',
        'resultUrl' => 'payment/result',
        'successUrl' => 'payment/success',
        'failureUrl' => 'payment/failure'
    ]
];