<?php
namespace sointula\shop\backend\components\form;

use yii\base\Model;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CombinationImageForm extends Model
{
    public $product_image_id;

    public function rules()
    {
        return [
            [['product_image_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_image_id' => \Yii::t('shop', 'Image'),
        ];
    }
}