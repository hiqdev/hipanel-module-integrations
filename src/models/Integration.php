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

class Integration extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'client_id', 'type_id', 'state_id'], 'integer'],
            [['client', 'state', 'provider', 'provider_like', 'name', 'url', 'login', 'access', 'password'], 'string'],

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
        return array_merge(parent::attributeLabels(), []);
    }
}
