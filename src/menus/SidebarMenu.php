<?php
/**
 * Integrations management plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-integrations
 * @package   hipanel-module-integrations
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\integrations\menus;

use Yii;

class SidebarMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        return Yii::$app->user->can('manage') ? [
            'settings' => [
                'label' => Yii::t('hipanel.integrations', 'Settings'),
                'url' => '#',
                'icon' => 'fa-cog',
                'items' => [
                    'integrations' => [
                        'label' => Yii::t('hipanel.integrations', 'Integrations'),
                        'url' => ['@integration/index'],
                    ],
                ],
            ],
        ] : [];
    }
}
