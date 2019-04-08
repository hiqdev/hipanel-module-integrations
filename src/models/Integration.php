<?php
/**
 * Integrations management plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-integrations
 * @package   hipanel-module-integrations
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\integrations\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\models\Ref;
use Yii;

class Integration extends Model
{
    use ModelTrait;

    public const ACCESS_TYPE_PAYMENT = 'payment';
    public const ACCESS_TYPE_DOMAIN = 'domain';
    public const ACCESS_TYPE_CERTIFICATE = 'certificate';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'client_id', 'type_id', 'state_id'], 'integer'],
            [['client', 'state', 'provider', 'provider_like', 'name', 'url', 'login', 'access', 'password', 'type'], 'string'],

            // Create / Update
            [['id', 'client_id', 'type_id', 'state_id', 'provider_id'], 'integer', 'on' => ['create', 'update']],
            [['name', 'url', 'login', 'password'], 'string', 'on' => ['create', 'update']],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'provider' => Yii::t('hipanel.integrations', 'Provider'),
            'provider_like' => Yii::t('hipanel.integrations', 'Provider')
        ]);
    }

    public function getProvider()
    {
        return $this->hasOne(Provider::class, ['id' => 'provider_id']);
    }

    public function getTypes()
    {
        return Ref::getList('type,api');
    }

    public function getStates()
    {
        return Ref::getList('state,access');
    }
}
