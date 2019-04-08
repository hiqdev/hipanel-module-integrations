<?php

namespace hipanel\modules\integrations\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class IntegrationRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'name',
                    'provider_like',
                    'client',
                    'type',
                    'state',
                ],
            ],
        ]);
    }
}
