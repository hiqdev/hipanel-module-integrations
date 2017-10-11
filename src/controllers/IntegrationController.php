<?php
/**
 * Integrations management plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-integrations
 * @package   hipanel-module-integrations
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

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
                'data' => function ($action) {
                    return [
                        'providers' => $action->controller->getProviders(),
                        'states' => $action->controller->getStates(),
                    ];
                },
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
        return range(1, 7);
    }

    public function getStates()
    {
        return $this->getRefs('state,access', 'hipanel');
    }
}
