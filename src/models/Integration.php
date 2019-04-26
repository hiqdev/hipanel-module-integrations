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
use hipanel\modules\integrations\data\ProvidersDataProvider;
use Yii;
use yii\db\ActiveQuery;

class Integration extends Model
{
    use ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'client_id', 'type_id', 'state_id', 'provider_id'], 'integer'],
            [['client', 'state', 'provider_name', 'provider_label', 'name', 'url', 'login', 'access', 'password', 'type', 'type_label', 'state_label', 'currency'], 'string'],
            ['id', 'required', 'on' => 'delete'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'provider_name' => Yii::t('hipanel.integrations', 'Provider'),
            'provider_label' => Yii::t('hipanel.integrations', 'Provider'),
            'name' => Yii::t('hipanel.integrations', 'Name'),
        ]);
    }

    public function getProvider()
    {
        return new class($this) extends ActiveQuery
        {
            public function one($db = null)
            {
                /** @var ProvidersDataProvider $pdp */
                $pdp = Yii::$container->get(ProvidersDataProvider::class);

                $provider = reset(array_filter($pdp->getProviders(), function ($provider, $key) {
                    return $provider->id === $this->modelClass->provider_id;
                }, ARRAY_FILTER_USE_BOTH));
                if ($provider) {
                    $provider->trigger(Provider::EVENT_AFTER_FIND);
                }

                return $provider;
            }
        };
    }

    public function getTypes(): ?array
    {
        return Ref::getList('type,api');
    }

    public function getStates(): ?array
    {
        return Ref::getList('state,access');
    }

    public function getCreateRoute(): array
    {
        return $this->buildUrl('create', $this);
    }

    public function getUpdateRoute(): array
    {
        return $this->buildUrl('update', $this);
    }

    public static function buildUrl(string $scenario, Integration $integration): array
    {
        return ["@integration/{$scenario}-{$integration->provider->name}", 'id' => $integration->id];
    }

    public function getPageTitle(): string
    {
        return $this->access;
    }
}
