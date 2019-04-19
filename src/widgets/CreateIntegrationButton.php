<?php

namespace hipanel\modules\integrations\widgets;

use hipanel\helpers\Url;
use hipanel\modules\integrations\data\ProvidersDataProvider;
use hipanel\modules\integrations\models\Provider;
use hiqdev\paymenticons\yii2\PaymentIconsAsset;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ListView;

class CreateIntegrationButton extends Widget
{
    /**
     * @var array
     */
    public $allowedTypes = [];

    /**
     * @var ProvidersDataProvider
     */
    private $providersDataProvider;

    public function init()
    {
        PaymentIconsAsset::register($this->view);
        $this->providersDataProvider = Yii::createObject(['class' => ProvidersDataProvider::class]);
        $this->view->on(View::EVENT_END_BODY, function ($event) {
            foreach ($this->getProviderVariantsByType() as $type => $providers) {
                Modal::begin([
                    'id' => 'choose-provider-modal-for-' . $type,
                    'size' => Modal::SIZE_LARGE,
                    'header' => Html::tag('h4', Yii::t('hipanel.integrations', 'Choose provider'), ['class' => 'modal-title']),
                    'toggleButton' => false,
                ]);

                echo ListView::widget([
                    'layout' => '{items}',
                    'options' => ['class' => 'provider-variants'],
                    'itemOptions' => ['class' => 'provider-variant'],
                    'dataProvider' => (new ArrayDataProvider(['allModels' => $providers, 'pagination' => false])),
                    'itemView' => function (Provider $provider, int $key, int $index, ListView $widget): string {
                        $html = '';
                        $html .= Html::beginTag('a', ['href' => Url::to('@integration/create-' . $provider->name)]);
                        if ($provider->hasImage()) {
                            $html .= Html::img($provider->image->src);
                        } else {
                            $html .= Html::tag('div', $provider->label, ['class' => 'without-image']);
                        }
                        $html .= Html::endTag('a');

                        return $html;
                    }
                ]);

                Modal::end();
            }
        });
    }

    public function run()
    {
        return $this->render('CreateIntegrationButton', [
            'typeVariants' => $this->getTypeVariants(),
            'providerVariants' => $this->getProviderVariantsByType(),
        ]);
    }

    public function getProviderVariantsByType(): array
    {
        return $this->providersDataProvider->getProvidersByType();
    }

    public function getTypeVariants(): array
    {
        $map = [
            'payment' => ['icon' => 'dollar'],
            'domain' => ['icon' => 'globe'],
            'certificate' => ['icon' => 'shield'],
        ];
        $variants = [];

        foreach ($this->allowedTypes as $type => $label) {
            $variants[$type] = [
                'label' => Yii::t('hipanel.integrations', 'Create {label} access', ['label' => Yii::t('hipanel.integrations', strtolower($label))]),
                'icon' => $map[$type]['icon'],
                'visible' => true,
            ];
        }

        return $variants;
    }
}
