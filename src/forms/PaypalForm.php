<?php

namespace hipanel\modules\integrations\forms;

use Yii;

class PaypalForm extends DefaultForm
{
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'login' => Yii::t('hipanel.integrations', 'Email'),
        ]);
    }
}
