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

use hiqdev\yii2\menus\Menu as MenuAlias;
use Yii;

class IntegrationActionsMenu extends MenuAlias
{
    public $model;

    public function items()
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@integration/view', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            'update' => [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => $this->model->updateRoute,
                'visible' => Yii::$app->user->can('integration.update'),
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            'enable' => [
                'label' => Yii::t('hipanel', 'Enable'),
                'icon' => 'fa-pencil',
                'url' => ['@integration/enable', 'id' => $this->model->id],
                'encode' => false,
                'visible' => Yii::$app->user->can('integration.update'),
                'linkOptions' => [
                    'data' => [
                        'method' => 'POST',
                        'pjax' => '0',
                    ],
                ],
            ],
            'disable' => [
                'label' => Yii::t('hipanel', 'Disable'),
                'icon' => 'fa-pencil',
                'url' => ['@integration/disable', 'id' => $this->model->id],
                'encode' => false,
                'visible' => Yii::$app->user->can('integration.update'),
                'linkOptions' => [
                    'data' => [
                        'method' => 'POST',
                        'pjax' => '0',
                    ],
                ],
            ],
            'delete' => [
                'label' => Yii::t('hipanel', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@integration/delete', 'id' => $this->model->id],
                'encode' => false,
                'visible' => Yii::$app->user->can('integration.delete'),
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel', 'Are you sure you want to delete this item?'),
                        'method' => 'POST',
                        'pjax' => '0',
                    ],
                ],
            ],
        ];
    }
}
