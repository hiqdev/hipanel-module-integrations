<?php

namespace hipanel\modules\integrations\data;

use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\helpers\ArrayHelper;
use hipanel\modules\integrations\forms\DefaultForm;
use hipanel\modules\integrations\models\Provider;
use hiqdev\hiart\Collection;
use yii\base\Action;
use yii\base\Application;
use Yii;

final class ProvidersDataProvider
{
    /**
     * @var Application
     */
    private $app;

    public static function getFormClassByProvider(Provider $provider): string
    {
        $className = sprintf('\hipanel\modules\integrations\forms\%sForm', ucfirst($provider->name));

        return class_exists($className) ? $className : DefaultForm::class;
    }

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getProviders(): array
    {
        $providers = $this->allProviders = $this->app->cache->getOrSet([__METHOD__, $this->app->user->id], function () {
            return Provider::find()->where(['state' => 'ok'])->limit(-1)->all();
        }, 1800); // 30 minutes

        return $providers;
    }

    public function getProvidersByType(): array
    {
        return ArrayHelper::index($this->getProviders(), null, 'type');
    }

    public function getProviderActions(): array
    {
        $actions = [];

        foreach ($this->getProviders() as $provider) {
            $class = self::getFormClassByProvider($provider);

            $actions['create-' . $provider->name] = [
                'class' => SmartCreateAction::class,
                'view' => 'create',
                'collection' => [
                    'class' => Collection::class,
                    'model' => new $class([
                        'scenario' => 'create',
                        'data' => $provider->data,
                        'provider_id' => $provider->id,
                        'provider_name' => $provider->name,
                        'provider_type' => $provider->type,
                    ]),
                    'scenario' => 'create',
                ],
                'success' => Yii::t('hipanel.integrations', '{type} access has been created', ['type' => ucfirst($provider->label ?? $provider->name)]),
            ];
            $actions['update-' . $provider->name] = [
                'class' => SmartUpdateAction::class,
                'view' => 'update',
                'collection' => [
                    'class' => Collection::class,
                    'model' => new $class([
                        'scenario' => 'update',
                        'data' => $provider->data,
                        'provider_id' => $provider->id,
                        'provider_name' => $provider->name,
                        'provider_type' => $provider->type,
                    ]),
                    'scenario' => 'update',
                ],
                'data' => function (Action $action, array $data) use ($class) {
                    $result = [];
                    foreach ($data['models'] as $model) {
                        $result['models'][] = $class::fromIntegration($model);
                    }
                    $result['model'] = reset($result['models']);

                    return $result;
                },
                'success' => Yii::t('hipanel.integrations', '{type} access has been updated', ['type' => ucfirst($provider->label ?? $provider->name)]),
            ];
            $actions["validate-{$provider->name}-form"] = [
                'class' => ValidateFormAction::class,
                'validatedInputId' => false,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new $class(),
                ],
            ];
        }

        return $actions;
    }
}
