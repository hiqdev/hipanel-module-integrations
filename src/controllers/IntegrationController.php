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
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\integrations\data\ProvidersDataProvider;
use Yii;
use yii\base\Module;

class IntegrationController extends CrudController
{
    /**
     * @var ProvidersDataProvider
     */
    private $providersDataProvider;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'create' => 'integration.create',
                    'update' => 'integration.update',
                    'delete' => 'integration.delete',
                    '*' => 'integration.read',
                ],
            ],
        ]);
    }

    /**
     * IntegrationController constructor.
     * @param $id
     * @param Module $module
     * @param ProvidersDataProvider $providersDataProvider
     * @param array $config
     */
    public function __construct($id, Module $module, ProvidersDataProvider $providersDataProvider, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->providersDataProvider = $providersDataProvider;
    }

    public function actions()
    {
        $actions = array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
                'data' => function ($action) {
                    return [
                        'states' => $action->controller->getStates(),
                        'providerTypes' => $action->controller->getProviderTypes(),
                    ];
                },
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel.integrations', 'Integration has been deleted'),
                'error' => Yii::t('hipanel.integrations', 'Failed to delete integration'),
            ]
        ], $this->getMandatoryActions());

        return $actions;
    }

    public function getStates()
    {
        return $this->getRefs('state,access', 'hipanel');
    }

    public function getProviderTypes(): ?array
    {
        $types = $this->getRefs('type,provider');

        return array_filter($types, function (string $label, string $type): bool {
            $availableTypes = Yii::$app->user->can('resell') ? ['payment'] : ['payment', 'certificate', 'domain'];
            return in_array($type, $availableTypes, true); // only these types are available for now
        }, ARRAY_FILTER_USE_BOTH);
    }

    private function getMandatoryActions(): array
    {
        return $this->providersDataProvider->getProviderActions();
    }
}
