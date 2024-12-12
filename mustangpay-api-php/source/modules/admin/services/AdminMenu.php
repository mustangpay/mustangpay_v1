<?php

namespace app\modules\admin\services;

use Yii;

class AdminMenu
{
    public static function listMenuByUser()
    {
        $menus = [
            [
                'label' => false,
                'items' => [
                    ['label' => '<i class="fa fa-home fa-fw fa-lg"></i>' . Yii::t('app.admin', '管理首页'), 'url' => ['default/index']]
                ]
            ],
            [
                'label' => Yii::t('app.admin', '主要功能'), 'items' => self::system(),
            ]
        ];
        return array_merge($menus, self::listMenu());
    }

    public static function system()
    {
        $paramsMenu = [
            [
                'visible' => YII_DEBUG, 'label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', '设置分类'), 'url' => ['param/param-category'], 'submenuTemplate' => '',
                'items' => [
                    ['url' => ['param/param-category-create']],
                    ['url' => ['param/param-category-update']],
                    ['url' => ['param/param-category-delete']],
                ]
            ],
            ['label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', '设置管理'), 'url' => ['param/param'], 'submenuTemplate' => '',
                'items' => [
                    ['url' => ['param/param-create']],
                    ['url' => ['param/param-update']],
                    ['url' => ['param/param-delete']],
                ]
            ],
            ['label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', '网站设置'), 'url' => ['param/set']],
            [
                'visible' => YII_DEBUG, 'label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', 'SEO管理'), 'url' => ['seo/seo'], 'submenuTemplate' => '',
                'items' => [
                    ['url' => ['seo/seo-create']],
                    ['url' => ['seo/seo-update']],
                    ['url' => ['seo/seo-delete']],
                ]
            ],
            ['label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', 'SEO设置'), 'url' => ['seo/set']],
            [
                'visible' => YII_DEBUG, 'label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', '数据库备份'), 'url' => ['backup/tables'], 'submenuTemplate' => '',
                'items' => [
                    ['url' => ['backup/backup']],
                ]
            ],
            ['label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', '单页管理'),
                'url' => ['article/page'],
                'submenuTemplate' => '',
                'items' => [
                    ['url' => ['article/page-create']],
                    ['url' => ['article/page-update']],
                ]
            ],
        ];
        return [
            ['label' => '<i class="fa fa-cogs fa-fw"></i>' . Yii::t('app.admin', '网站设置'), 'url' => 'javascript:;',
                'options' => ['class' => 'has-next'],
                'items' => $paramsMenu
            ],
            ['label' => '<i class="fa fa-unlock-alt fa-fw"></i>' . Yii::t('app.admin', '修改密码'), 'url' => ['default/reset-pwd']],
        ];
    }

    public static function listMenu()
    {
        return [
            ['label' => Yii::t('app.admin', '信息管理'), 'items' => [
                ['label' => '<i class="fa fa-list fa-fw"></i>' . Yii::t('app.admin', '栏目管理'), 'url' => 'javascript:;',
                    'options' => ['class' => 'has-next'],
                    'items' => [
                        ['label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', '分类管理'),
                            'url' => ['article/category'],
                            'submenuTemplate' => '',
                            'items' => [
                                ['url' => ['article/category-create']],
                                ['url' => ['article/category-update']],
                                ['url' => ['article/category-view']],
                                ['url' => ['article/category-delete']],
                            ]
                        ],
                        ['label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', '文章管理'),
                            'url' => ['article/news'],
                            'submenuTemplate' => '',
                            'items' => [
                                ['url' => ['article/news-create']],
                                ['url' => ['article/news-update']],
                                ['url' => ['article/news-delete']],
                            ]
                        ],
                    ],
                ],
            ]],
            ['label' => Yii::t('app.admin', '用户管理'), 'items' => [
                ['label' => '<i class="fa fa-user fa-fw"></i>' . Yii::t('app.admin', '用户管理'), 'url' => 'javascript:;',
                    'options' => ['class' => 'has-next'],
                    'items' => [
                        ['label' => '<i class="fa fa-circle-o fa-fw"></i>' . Yii::t('app.admin', '用户管理'),
                            'url' => ['member/member'],
                            'submenuTemplate' => '',
                            'items' => [
                                ['url' => ['member/member-create']],
                                ['url' => ['member/member-update']],
                                ['url' => ['member/member-delete']],
                            ]
                        ],
                    ],
                ],
            ]],
        ];
    }
}
