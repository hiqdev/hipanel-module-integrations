<?php

namespace hipanel\modules\integrations\widgets;

use hipanel\models\Ref;
use hipanel\widgets\Type;

class IntegrationType extends Type
{
    /**
     * @var array
     */
    public $defaultValues = [
        'default' => [
            '*',
        ],
    ];

    /**
     * @var string
     */
    public $field = 'type';

    /**
     * @var string
     */
    public $i18nDictionary = 'hipanel.integrations.types';

    protected function getModelLabel(): string
    {
        $types = Ref::getListRecursively('type,api', false);

        return $types[$this->getFieldValue()] ?? $this->getFieldValue();
    }
}


