<?php
const STATUS_ACTIVE = 1;
const STATUS_INACTIVE = 0;
const STATUS_DELETED = -1;

const STATUS_YES = 1;
const STATUS_NO = 0;
return [
    // 图片保存路径
    'uploadPath' => '/data/',
    'adminEmail' => 'admin@example.com',
    'list' => [
        'read' => [STATUS_ACTIVE => '已读', STATUS_INACTIVE => '未读'],
        'qy_ty' => [STATUS_ACTIVE => '启用', STATUS_INACTIVE => '停用'],
        's_f' => [STATUS_YES => '是', STATUS_NO => '否'],
    ],
    'apiList' => [
        'webpayment' => [
            'label' => '预下单',
            'class' => 'app\controllers\webpayment\cashierController',
        ]
    ],
    'certFile' => [
        'mustangPayPublicKeyPath' => '@webroot/source/config/cert/mustangpay.pub.key',
        'rsaPrivateKeyPath' => '@webroot/source/config/cert/rsa.pri.key',
        'rsaPublicKeyPath' => '@webroot/source/config/cert/rsa.pub.key',
    ],
    'user.log.level' => ['error' , 'warning' , 'success' , 'info'],
];
