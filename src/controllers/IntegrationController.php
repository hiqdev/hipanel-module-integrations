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
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\modules\integrations\forms\CertificateForm;
use hipanel\modules\integrations\forms\DomainForm;
use hipanel\modules\integrations\forms\PaymentForm;
use hipanel\modules\integrations\models\Integration;
use hiqdev\hiart\Collection;
use Yii;

class IntegrationController extends CrudController
{
    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
                'data' => function ($action) {
                    return [
                        'states' => $action->controller->getStates(),
                    ];
                },
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
        ], $this->getMandatoryActions());
    }

    public function getStates()
    {
        return $this->getRefs('state,access', 'hipanel');
    }

    public function getSpecificFormName(): ?string
    {
        foreach ([
                     Integration::ACCESS_TYPE_PAYMENT,
                     Integration::ACCESS_TYPE_CERTIFICATE,
                     Integration::ACCESS_TYPE_DOMAIN,
                 ] as $accessType) {
            if (stristr($this->action->id, $accessType)) {
                return $accessType;
            }
        }

        return null;
    }

    private function getMandatoryActions(): array
    {
        $actions = [];
        $map = [
            Integration::ACCESS_TYPE_PAYMENT => PaymentForm::class,
            Integration::ACCESS_TYPE_DOMAIN => DomainForm::class,
            Integration::ACCESS_TYPE_CERTIFICATE => CertificateForm::class,
        ];

        foreach ($map as $type => $class) {
            $actions['create-' . $type] = [
                'class' => SmartCreateAction::class,
                'view' => 'create',
                'collection' => [
                    'class' => Collection::class,
                    'model' => new $class(['scenario' => 'create']),
                    'scenario' => 'create',
                ],
                'success' => Yii::t('hipanel.integrations', '{type} access has been created', ['type' => ucfirst($type)]),
            ];
            $actions['update-' . $type] = [
                'class' => SmartUpdateAction::class,
                'view' => 'update',
                'collection' => [
                    'class' => Collection::class,
                    'model' => new $class(['scenario' => 'update']),
                    'scenario' => 'update',
                ],
                'success' => Yii::t('hipanel.integrations', '{type} access has been updated', ['type' => ucfirst($type)]),
            ];
            $actions["validate->{$type}-form"] = [
                'class' => ValidateFormAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new $class(),
                ],
            ];
        }

        return $actions;
    }
}
