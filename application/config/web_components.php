<?php

return [
    'db' => require(__DIR__ . '/db.php'),
    'user' => [
        'class' => 'app\components\User',
        'identityClass' => 'app\models\User',
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
                'logVars' => ['@runtime/logs/cron.log']
            ]
        ]
    ],
    'perfectMoney' => [
        'class' => 'yiidreamteam\perfectmoney\Api',
        'accountId' => '5816901',
        'accountPassword' => 'qwerty123',
        'walletNumber' => 'U13860909',
        'merchantName' => 'GeorgeLemish',
        'alternateSecret' => '1H3f88y7FTZPLoPXdImityMEU',
        'resultUrl' => 'payment/result',
        'successUrl' => 'payment/success',
        'failureUrl' => 'payment/failure'
    ]
];