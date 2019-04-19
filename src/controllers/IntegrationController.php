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
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\modules\integrations\data\ProvidersDataProvider;
use yii\base\Module;

class IntegrationController extends CrudController
{
    /**
     * @var ProvidersDataProvider
     */
    private $providersDataProvider;

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

        return array_filter($types, function ($label, $type) {
            return in_array($type, ['payment', 'certificate', 'domain']); // only this types available for now
        }, ARRAY_FILTER_USE_BOTH);
    }

    private function getMandatoryActions(): array
    {
        return $this->providersDataProvider->getProviderActions();
    }
}
