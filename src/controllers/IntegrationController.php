<?php

namespace hipanel\modules\integrations\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ViewAction;
use Yii;

class IntegrationController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel.integrations', 'Item has been created'),
                'data' => function ($action) {
                    return [
                        'providers' => $action->controller->getProviders(),
                    ];
                },
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel.integrations', 'Item has been updated'),
                'data' => function ($action) {
                    return [
                        'providers' => $action->controller->getProviders(),
                    ];
                },
            ],
        ];
    }

    public function getProviders()
    {
        return [];
    }
}
