<?php

namespace hipanel\modules\integrations\forms;

use hipanel\modules\integrations\widgets\IntegrationFormBuilder;
use Yii;

class YandexmoneyForm extends DefaultForm
{
    const METHOD_CARD = 'AC';
    const METHOD_WALLET = 'PC';
    const METHOD_PHONE = 'MC';

    public $payment_method;

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['payment_method'], 'string'],
        ]);
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'login' => Yii::t('hipanel.integrations', 'Purse'),
            'password' => Yii::t('hipanel.integrations', 'Secret word'),
            'payment_method' => Yii::t('hipanel.integrations', 'Payment method'),
        ]);
    }

    public function getFormConfig(): array
    {
        return array_merge(parent::getFormConfig(), [
            'payment_method' => [
                'type' => IntegrationFormBuilder::INPUT_DROPDOWN_LIST,
                'items'=> $this->getPaymentMethods(),
            ],
        ]);
    }

    public function getPaymentMethods(): array
    {
        return [
            '' => '',
            self::METHOD_CARD => Yii::t('hipanel.integrations.types', 'Bank card'),
            self::METHOD_WALLET => Yii::t('hipanel.integrations.types', 'Purse'),
            self::METHOD_PHONE => Yii::t('hipanel.integrations.types', 'Phone'),
        ];
    }

}
