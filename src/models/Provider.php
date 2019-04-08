<?php

namespace hipanel\modules\integrations\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;

class Provider extends Model
{
    use ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'type_id', 'state_id'], 'integer'],
            [['name', 'label', 'url', 'login', 'password'], 'string'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), []);
    }
}
