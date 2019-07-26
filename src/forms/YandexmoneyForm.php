<?php

namespace hipanel\modules\integrations\forms;

use Yii;

class YandexmoneyForm extends DefaultForm
{
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'login' => Yii::t('hipanel.integrations', 'Purse'),
            'password' => Yii::t('hipanel.integrations', 'Secret word'),
        ]);
    }
}
