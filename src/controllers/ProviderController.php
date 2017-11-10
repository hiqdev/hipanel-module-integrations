<?php

namespace hipanel\modules\integrations\controllers;

use hipanel\actions\ComboSearchAction;
use yii\base\Action;

class ProviderController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return array_merge(parent::actions(), [
            'get-providers' => [
                'class' => ComboSearchAction::class,
                'on beforeSave' => function ($event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    $action->dataProvider->query->action('Search');
                },
            ],
        ]);
    }
}
