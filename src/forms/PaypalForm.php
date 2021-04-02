<?php

namespace hipanel\modules\integrations\forms;

use hipanel\modules\integrations\widgets\IntegrationFormBuilder;
use Yii;

class PaypalForm extends DefaultForm
{
    public $system_commission;

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['system_commission'], 'number'],
        ]);
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'login' => Yii::t('hipanel.integrations', 'Email'),
            'system_commission' => Yii::t('hipanel.integrations', 'System commission'),
        ]);
    }

    public function getFormConfig(): array
    {
        return array_merge(parent::getFormConfig(), [
            'system_commission' => [
                'type' => IntegrationFormBuilder::INPUT_HTML5,
                'html5type' => 'number',
                'options' => [
                    'min' => 0,
                    'step' => 0.01
                ],
            ],
        ]);
    }


}
