<?php

// $server = "localhost";
// $username = "eulims";
// $password = "eulims";

    $server = "192.168.1.96";
    $username = "eulims";
    $password = "D05793ul1ms!@#$%";




return [
    'db'=>[
        'class' => 'yii\db\Connection',  
        'dsn' => 'mysql:host='.$server.';dbname=eulims',
        'username' => $username,
        'password' => $password,
        'charset' => 'utf8',
        'tablePrefix' => 'tbl_',
    ],
    'labdb'=>[
        'class' => 'yii\db\Connection',  
       'dsn' => 'mysql:host='.$server.';dbname=eulims_lab',
        'username' => $username,
        'password' => $password,
        'charset' => 'utf8',
        'tablePrefix' => 'tbl_',
    ],
    'inventorydb'=>[
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host='.$server.';dbname=eulims_inventory',
        'username' => $username,
        'password' => $password,
        'charset' => 'utf8',
        'tablePrefix' => 'tbl_',
    ],
    'financedb'=>[
        'class' => 'yii\db\Connection',  
        'dsn' => 'mysql:host='.$server.';dbname=eulims_finance',
        'username' => $username,
        'password' => $password,
        'charset' => 'utf8',
        'tablePrefix' => 'tbl_',
    ],
    'addressdb'=>[
        'class' => 'yii\db\Connection',  
        'dsn' => 'mysql:host='.$server.';dbname=eulims_address',
        'username' => $username,
        'password' => $password,
        'charset' => 'utf8',
        'tablePrefix' => 'tbl_',
    ],
    'referraldb'=>[
        'class' => 'yii\db\Connection',  
        'dsn' => 'mysql:host='.$server.';dbname=eulims_referral_lab',
        'username' => $username,
        'password' => $password,
        'charset' => 'utf8',
        'tablePrefix' => 'tbl_',
    ],
];