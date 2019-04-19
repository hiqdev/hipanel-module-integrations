<?php

namespace hipanel\modules\integrations\widgets;

use hipanel\models\Ref;
use hipanel\widgets\Type;

class IntegrationType extends Type
{
    public $defaultValues = [
        'default' => [
            '*',
        ],
    ];

    public $field = 'type';

    public $i18nDictionary = 'hipanel.integrations.types';

    protected function getModelLabel(): string
    {
        $types = Ref::getListRecursively('type,api', false);

        return $types[$this->getFieldValue()] ?? $this->getFieldValue();
    }
}


