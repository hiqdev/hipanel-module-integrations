<?php

namespace hipanel\modules\integrations\models;

class Provider extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'type_id', 'state_id'], 'integer'],
            [['name', 'label'], 'string'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), []);
    }

    public function getProvider()
    {
        return $this->hasOne(Provider::class, ['id' => 'provider_id']);
    }
}
