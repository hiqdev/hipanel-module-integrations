<?php

namespace hipanel\modules\integrations\forms;

use hipanel\modules\integrations\widgets\IntegrationFormBuilder;
use Yii;

class PaxumForm extends DefaultForm
{
    public $system_fee;

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['system_fee'], 'integer'],
        ]);
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'login' => Yii::t('hipanel.integrations', 'Email'),
            'password' => Yii::t('hipanel.integrations', 'Password'),
            'system_fee' => Yii::t('hipanel.integrations', 'System fee in cents'),
        ]);
    }

    public function getFormConfig(): array
    {
        return array_merge(parent::getFormConfig(), [
            'system_fee' => [
                'type' => IntegrationFormBuilder::INPUT_HTML5,
                'html5type' => 'number',
                'options' => [
                    'min' => 0,
                    'step' => 1,
                ],
            ],
        ]);
    }


}
