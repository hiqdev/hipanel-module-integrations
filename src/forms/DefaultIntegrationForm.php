<?php

namespace hipanel\modules\integrations\forms;

use hipanel\models\Ref;
use hipanel\modules\integrations\models\Provider;
use Yii;
use yii\base\Model;

abstract class DefaultIntegrationForm extends Model
{
    public $id;

    public $name;

    public $url;

    public $login;

    public $password;

    public $provider_id;

    public function init()
    {
        parent::init();
        $this->fillDefaultInputs();
    }

    public function rules()
    {
        return [
            [['id', 'provider_id'], 'integer'],
            ['url', 'url'],
            [['name', 'login', 'password'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hipanel.integrations', 'Name'),
            'url' => Yii::t('hipanel.integrations', 'URL'),
            'login' => Yii::t('hipanel.integrations', 'Login'),
            'password' => Yii::t('hipanel.integrations', 'Password'),
        ];
    }

    private function fillDefaultInputs(): void
    {
    }

    public function getProvider()
    {
        return $this->hasOne(Provider::class, ['id' => 'provider_id']);
    }

    public function getProviders()
    {
        return Provider::find()->asArray()->all();
    }

    public function getTypes()
    {
        return Ref::getList('type,api');
    }
}
