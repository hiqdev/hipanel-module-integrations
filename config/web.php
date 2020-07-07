<?php
/**
 * Integrations management plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-integrations
 * @package   hipanel-module-integrations
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

return [
    'aliases' => [
        '@integration' => '/integrations/integration',
    ],
    'modules' => [
        'integrations' => [
            'class' => \hipanel\modules\integrations\Module::class,
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'hipanel.integrations' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
                'hipanel.integrations.types' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            \hipanel\models\ObjClass::class => [
                'knownClasses' => [
                    'access' => [
                        'color' => 'info',
                        'alias' => 'integration',
                        'label' => function () {
                            return Yii::t('hipanel.integrations', 'Integration');
                        },
                    ],
                ],
            ],
            \hiqdev\thememanager\menus\AbstractSidebarMenu::class => [
                'add' => [
                    'integrations' => [
                        'menu' => [
                            'class' => \hipanel\modules\integrations\menus\SidebarMenu::class,
                        ],
                        'where' => [
                            'after' => ['tickets', 'finance', 'clients', 'dashboard'],
                            'before' => ['hosting', 'server', 'domain', 'stock'],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
