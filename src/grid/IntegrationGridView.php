<?php

namespace hipanel\modules\integrations\grid;

use hipanel\modules\integrations\menus\IntegrationActionsMenu;
use hiqdev\yii2\menus\grid\MenuColumn;
use hipanel\grid\MainColumn;

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

