<?php

namespace hipanel\modules\integrations\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use Yii;
use yii\base\DynamicModel;
use yii\db\ActiveQuery;

class Provider extends Model
{
    use ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'type_id', 'state_id'], 'integer'],
            [['name', 'label', 'type', 'state', 'data'], 'string'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), []);
    }

    public function hasImage(): bool
    {
        return is_string($this->image->src);
    }

    public function getImage()
    {
        return new class($this) extends ActiveQuery
        {
            public function one($db = null)
            {
                $image = new DynamicModel(['src']);
                $image->addRule(['src'], 'string');
                if ($src = $this->modelClass->findImageByName()) {
                    $image->src = $src;
                }

                return $image;
            }
        };
    }

    public function findImageByName(): ?string
    {
        $src = null;
        $pathToImage = Yii::getAlias(sprintf('%s/%s.png', '@vendor/hiqdev/payment-icons/src/assets/png/sm', $this->name));
        if (is_file($pathToImage)) {
            Yii::$app->assetManager->publish($pathToImage);
            $src = Yii::$app->assetManager->getPublishedUrl($pathToImage);
        }

        return $src;
    }
}
