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

use hipanel\grid\BoxedGridView;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\modules\integrations\menus\IntegrationActionsMenu;
use hipanel\modules\integrations\models\Integration;
use hipanel\modules\integrations\widgets\IntegrationType;
use hipanel\modules\integrations\widgets\IntegrationTypeFilter;
use hipanel\widgets\State;
use hiqdev\yii2\menus\grid\MenuColumn;

class IntegrationGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'name' => [
                'class' => MainColumn::class,
                'filterAttribute' => 'name_ilike',
            ],
            'state' => [
                'class' => RefColumn::class,
                'gtype' => 'state,access',
                'format' => 'html',
                'value' => function ($model) {
                    return State::widget([
                        'model' => $model,
                        'values' => [
                            'info' => ['ok'],
                            'warning' => ['disabled']
                        ],
                    ]);
                }
            ],
            'provider_label' => [
                'filterAttribute' => 'provider_name_ilike',
            ],
            'type' => [
                'filter' => function ($column, $filterModel) {
                    return IntegrationTypeFilter::widget([
                        'options' => ['class' => 'form-control text-right', 'style' => 'max-width: 12em'],
                        'attribute' => 'type',
                        'model' => $filterModel,
                    ]);
                },
                'sortAttribute' => 'type',
                'format' => 'raw',
                'value' => function (Integration $model): string {
                    return IntegrationType::widget([
                        'model' => $model,
                        'field' => 'type',
                        'labelField' => 'type_label',
                    ]);
                },
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'filterOptions' => ['class' => 'narrow-filter'],
                'menuClass' => IntegrationActionsMenu::class,
            ],
        ]);
    }
}
