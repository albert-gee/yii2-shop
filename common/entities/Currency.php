<?php

namespace sointula\shop\common\entities;

use Yii;



use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "shop_currency".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $value
 * @property string $date
 */
class Currency extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'double'],
            [['date'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_currency';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'value' => Yii::t('shop', 'Value'),
            'date' => Yii::t('shop', 'Date'),
        ];
    }

    /**
     * This method return last currency instance
     *
     * @return Currency
     */
    public static function findCurrent() {
        return self::find()->orderBy('id DESC')->one();
    }

    /**
     * This method return last currency value
     *
     * @return integer
     */
    public static function currentCurrency() {
        $currency = self::findCurrent();
        if (!empty($currency))
            return $currency->value;
        else return 0;
    }
}
