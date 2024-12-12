<?php
return [
    'class' => 'yii\swiftmailer\Mailer',
    'viewPath' => '@app/mail',
    //这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.qq.com',
    ],
];