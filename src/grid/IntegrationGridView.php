<?php
/**
 * Integrations management plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-integrations
 * @package   hipanel-module-integrations
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\integrations\grid;

use hipanel\grid\MainColumn;
use hipanel\modules\integrations\menus\IntegrationActionsMenu;
use hiqdev\yii2\menus\grid\MenuColumn;

class IntegrationGridView extends \hipanel\grid\BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'name' => [
                'class' => MainColumn::class,
            ],
            'state' => [
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'filterOptions' => ['class' => 'narrow-filter'],
                'menuClass' => IntegrationActionsMenu::class,
            ],
        ]);
    }
}
