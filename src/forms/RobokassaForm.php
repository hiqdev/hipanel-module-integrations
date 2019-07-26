<?php

namespace hipanel\modules\integrations\forms;

use Yii;

class RobokassaForm extends DefaultForm
{
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'login' => Yii::t('hipanel.integrations', 'Store ID'),
            'password' => Yii::t('hipanel.integrations', 'Password 1'),
            'key2' => Yii::t('hipanel.integrations', 'Password 2'),
            'url' => Yii::t('hipanel.integrations', 'Result URL'),
        ]);
    }
}
