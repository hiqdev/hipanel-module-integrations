<?php

namespace hipanel\modules\integrations\forms;

use hipanel\modules\integrations\widgets\IntegrationFormBuilder;
use Yii;

class FreekassaForm extends DefaultForm
{
    public $system_commission;


    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['login'], 'integer'],
            [['system_commission'], 'number'],
            [['key2'], 'safe'],
        ]);
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'login' => Yii::t('hipanel.integrations', 'ID'),
            'system_commission' => Yii::t('hipanel.integrations', 'System commission in percent'),
            'key2' => Yii::t('hipanel.integrations', 'Key'),
        ]);
    }

    public function getFormConfig(): array
    {
        return array_merge(parent::getFormConfig(), [
            'key2' => [
                'type' => IntegrationFormBuilder::INPUT_PASSWORD,
            ],
            'system_commission' => [
                'type' => IntegrationFormBuilder::INPUT_HTML5,
                'html5type' => 'number',
                'options' => [
                    'min' => 0,
                    'step' => 0.01,
                ],
            ],
        ]);
    }
}
