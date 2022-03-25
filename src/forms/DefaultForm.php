<?php

namespace hipanel\modules\integrations\forms;

use hipanel\models\Ref;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\integrations\helpers\ConfigSort;
use hipanel\modules\integrations\models\Integration;
use hipanel\modules\integrations\widgets\IntegrationFormBuilder;
use hiqdev\hiart\ActiveQuery;
use Yii;
use yii\base\Model;
use yii\helpers\Json;

class DefaultForm extends Model implements IntegrationFormInterface
{
    public $id;

    public $name;

    public $access;

    public $url;

    public $login;

    public $password;

    public $provider_id;

    public $provider_name;

    public $provider_type;

    public $client_id;

    public $type_id;

    public $currency;

    public $commission;

    public $key2;

    public $key3;

    public $data;

    public static function fromIntegration(Integration $integration, string $scenario = 'update'): self
    {
        $form = new static(['scenario' => $scenario]);
        foreach ($integration as $attribute => $value) {
            if ($form->hasProperty($attribute)) {
                $form->{$attribute} = $value;
            }
        }
        // setting form fields from data
        $form->data = $integration->provider->data;
        $form->provider_name = $integration->provider->name;

        return $form;
    }

    public function getDefaultFields(): array
    {
        $data = Json::decode($this->data);
        if (isset($data['api_type_id'])) {
            $data['type_id'] = $data['api_type_id'];
            unset($data['api_type_id']);
        }
        if ($data) {
            return array_filter($data, function ($value, $attribute) {
                return $this->hasProperty($attribute) ? $attribute : null;
            }, ARRAY_FILTER_USE_BOTH);
        }

        return $data ?? [];
    }

    protected function prepareDefaultFields(): void
    {
        $fields = $this->getDefaultFields();
        if ($fields && \is_countable($fields)) {
            foreach ($fields as $attribute => $value) {
                if ($attribute === 'api_type_id') {
                    $this->type_id = $value;
                } else {
                    $this->{$attribute} = $value;
                }
            }
        }
    }

    public function init()
    {
        parent::init();

        $this->prepareDefaultFields();
    }

    public function getIsNewRecord(): bool
    {
        return $this->id === null;
    }

    public function getPrimaryKey(): ?int
    {
        return $this->id;
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function getFormConfig(): array
    {
        $config = [];
        if ($this->isNewRecord) {
            $this->client_id = Yii::$app->user->identity->id;
            $this->name = $this->provider_name;
        } else {
            $config['id'] = [
                'type' => IntegrationFormBuilder::INPUT_HIDDEN,
                'label' => false,
            ];
        }

        $config['name'] = [
            'type' => IntegrationFormBuilder::INPUT_TEXT,
        ];

        if (Yii::$app->user->can('support')) {
            $config['client_id'] = [
                'type' => IntegrationFormBuilder::INPUT_WIDGET,
                'widgetClass' => ClientCombo::class,
            ];
        } else {
            $config['client_id'] = [
                'type' => IntegrationFormBuilder::INPUT_HIDDEN,
                'label' => false,
            ];
        }

        $config['provider_id'] = [
            'type' => IntegrationFormBuilder::INPUT_HIDDEN,
            'label' => false,
        ];

        if ($this->provider_type === 'payment') {
            $config['commission'] = [
                'type' => IntegrationFormBuilder::INPUT_HTML5,
                'html5type' => 'number',
            ];
        }

        foreach ($this->getDefaultFields() as $attribute => $value) {
            $cfg = [];
            if (strstr($attribute, 'url')) {
                $cfg['type'] = IntegrationFormBuilder::INPUT_HTML5;
                $cfg['html5type'] = 'url';
            } elseif ($attribute === 'type_id') {
                $cfg['type'] = IntegrationFormBuilder::INPUT_HIDDEN;
                $cfg['label'] = false;
            } elseif ($attribute === 'password') {
                $cfg['type'] = IntegrationFormBuilder::INPUT_PASSWORD;
            } elseif ($attribute === 'currency') {
                $cfg['type'] = IntegrationFormBuilder::INPUT_DROPDOWN_LIST;
                $cfg['options']['prompt'] = '--';
                $cfg['items'] = Ref::getList('type,currency', 'hipanel', [
                    'mapOptions' => ['to' => function (Ref $ref) {
                        return strtoupper($ref->name);
                    }],
                ]);
            } else {
                $cfg['type'] = IntegrationFormBuilder::INPUT_TEXT;
            }

            $config[$attribute] = $cfg;
        }

        return array_filter(ConfigSort::anyConfigs()->keys($config));
    }


    public function rules(): array
    {
        $rules = [
            [['id', 'provider_id', 'type_id', 'client_id'], 'integer'],
            [['commission'], 'number'],
            [['url'], 'url'],
            [['name', 'login', 'password', 'data', 'currency', 'key2', 'key3'], 'string'],
            [['name'], 'required', 'on' => ['create', 'update']],
            [['name'], 'unique', 'targetAttribute' => ['client_id', 'name'],
                'targetClass' => Integration::class,
                'filter' => function (ActiveQuery $query) {
                    $query->andWhere(['ne', 'id', $this->id]);
                },
                'message' => Yii::t('hipanel.integrations', 'Fields Client and Name are not unique'),
                'on' => ['create', 'update'],
            ],
        ];
        // Add default required
        $defaultFields = $this->getDefaultFields();
        unset($defaultFields['commission']);
        $rules[] = [array_keys($defaultFields), 'required', 'on' => ['create', 'update']];

        return $rules;
    }

    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('hipanel.integrations', 'Name'),
            'url' => Yii::t('hipanel.integrations', 'URL'),
            'login' => Yii::t('hipanel.integrations', 'Login'),
            'password' => Yii::t('hipanel.integrations', 'Password'),
            'client_id' => Yii::t('hipanel', 'Client'),
            'commission' => Yii::t('hipanel.integrations', 'Commission, %'),
            'currency' => Yii::t('hipanel.integrations', 'Currency'),
            'access' => Yii::t('hipanel.integrations', 'Access'),
        ];
    }

    /**
     * For compatibility with [[hiqdev\hiart\Collection]]
     *
     * @param $defaultScenario
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public function batchQuery($defaultScenario, $data = [], array $options = [])
    {
        $map = [
            'create' => 'create',
            'update' => 'update',
        ];
        $scenario = $map[$defaultScenario] ?? $defaultScenario;

        return (new Integration())->batchQuery($scenario, $data, $options);
    }

    public function getOldAttribute($attribute)
    {
        return $this->$attribute;
    }

    public function setOldAttribute($attribute, $value)
    {
        return true;
    }

    public function setOldAttributes($values)
    {
        return true;
    }

    public function afterSave()
    {
        return true;
    }
}
