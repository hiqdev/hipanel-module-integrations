<?php

namespace hipanel\modules\integrations\models;

use Yii;

class Integration extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'client_id', 'type_id', 'state_id', 'provider_id'], 'integer'],
            [['client', 'state', 'provider', 'name', 'url', 'login', 'access'], 'string'],
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
