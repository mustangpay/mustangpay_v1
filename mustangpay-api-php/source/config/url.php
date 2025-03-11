<?php

return [
    'gii' => 'gii',
    'gii/<controller:\w+>' => 'gii/<controller>',
    'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
    'xyadmin' => 'admin/default/index',
    'xyadmin/<action:[^\/]+>' => 'admin/default/<action>',
    'xyadmin/<action:\w+>/<op:(c|u|r|d|db|i|p|v)>/<id:\w+>' => 'admin/default/<action>',
    'xyadmin/<action:\w+>/<op:(c|r)>' => 'admin/default/<action>',
    'xyadmin/<action:\w+>' => 'admin/default/<action>',


    'cashier/<action:\w+>' => 'webpayment/cashier/<action>',

//    '' => 'site/index',

//    [
//        'pattern' => '<action:[^\/]+>/<type:\w+>/page-<page:\d+>',
//        'route' => 'site/<action>',
//        'suffix' => '/',
//    ],
//    [
//        'pattern' => '<action:[^\/]+>/page-<page:\d+>',
//        'route' => 'site/<action>',
//        'suffix' => '/',
//    ],
//

//    [
//        'pattern' => '<action:[^\/]+>',
//        'route' => 'site/<action>',
//    ],
];
