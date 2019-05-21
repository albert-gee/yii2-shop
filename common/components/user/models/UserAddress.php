<?php
namespace albertgeeca\shop\common\components\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_address".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $user_profile_id
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $house
 * @property string $apartment
 * @property integer $zipcode
 * @property integer $postoffice
 *
 * @property Profile $userProfile
 */
class UserAddress extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_profile_id', 'zipcode'], 'integer'],
            [['country', 'region', 'city', 'street', 'house'], 'string', 'max' => 255],
            [['apartment'], 'string', 'max' => 11],
            [['user_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['user_profile_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country' => Yii::t('shop', 'Country'),
            'region' => Yii::t('shop', 'Region'),
            'city' => Yii::t('shop', 'City'),
            'house' => Yii::t('shop', 'House'),
            'apartment' => Yii::t('shop', 'Apartment'),
            'zipcode' => Yii::t('shop', 'Zip-Code'),
            'post-office' => Yii::t('shop', 'Post office')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'user_profile_id']);
    }
}