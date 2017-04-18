<?php
namespace xalberteinsteinx\shop\common\components\user\models;

use xalberteinsteinx\shop\common\components\user\models\User;
use dektrium\user\traits\ModuleTrait;
use Yii;
use dektrium\user\models\Profile as BaseProfile;
/**
 * This file overrides standart model of the Dektrium project Yii2-user.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $avatar
 * @property integer $phone
 * @property string $info
 *
 * @property User $user
 * @property UserAddress[] $userAddresses
 */


class Profile extends BaseProfile
{
    use ModuleTrait;
    /** @var \dektrium\user\Module */
    protected $module;
    /** @inheritdoc */
    public function init()
    {
        $this->module = \Yii::$app->getModule('user');
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['phone'], 'string', 'max' => 25],
            [['info'], 'string', 'max' => 120],
            [['phone'], 'required'],
            [['name', 'surname', 'patronymic', 'avatar'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('cart', 'Name'),
            'surname' => Yii::t('cart', 'Surname'),
            'patronymic' => Yii::t('cart', 'Patronymic'),
            'avatar' => Yii::t('cart', 'Avatar'),
            'phone' => Yii::t('cart', 'Phone number'),
            'info' => Yii::t('cart', 'Information'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddresses()
    {
        return $this->hasMany(UserAddress::className(), ['user_profile_id' => 'id']);
    }

    /**
     * @return string
     *
     * Gets current user name with surname as string.
     */
    public function getUserNameWithSurname():string {

        $string = $this->name . ' ' . $this->surname;
        return $string;
    }

    /**
     * Validates the timezone attribute.
     * Adds an error when the specified time zone doesn't exist.
     * @param string $attribute the attribute being validated
     * @param array $params values for the placeholders in the error message
     */
    public function validateTimeZone($attribute, $params)
    {
        if (!in_array($this->$attribute, timezone_identifiers_list())) {
            $this->addError($attribute, \Yii::t('user', 'Time zone is not valid'));
        }
    }

    /**
     * Get the user's time zone.
     * Defaults to the application timezone if not specified by the user.
     * @return \DateTimeZone
     */
    public function getTimeZone()
    {
        try {
            return new \DateTimeZone($this->timezone);
        } catch (\Exception $e) {
            // Default to application time zone if the user hasn't set their time zone
            return new \DateTimeZone(\Yii::$app->timeZone);
        }
    }

    /**
     * Set the user's time zone.
     * @param \DateTimeZone $timezone the timezone to save to the user's profile
     */
    public function setTimeZone(\DateTimeZone $timeZone)
    {
        $this->setAttribute('timezone', $timeZone->getName());
    }

    /**
     * Converts DateTime to user's local time
     * @param \DateTime the datetime to convert
     * @return \DateTime
     */
    public function toLocalTime(\DateTime $dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new \DateTime();
        }

        return $dateTime->setTimezone($this->getTimeZone());
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }
}