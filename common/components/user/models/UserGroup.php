<?php
namespace albertgeeca\shop\common\components\user\models;
use bl\multilang\behaviors\TranslationBehavior;
use Yii;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "user_group".
 *
 * @property integer $id
 *
 * @property User $user
 * @property UserGroupTranslation[] $userGroupTranslations
 */
class UserGroup extends ActiveRecord
{

    const USER_GROUP_ALL_USERS = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_group';
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => UserGroupTranslation::className(),
                'relationColumn' => 'user_group_id'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'user_id' => Yii::t('shop', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroupTranslations()
    {
        return $this->hasMany(UserGroupTranslation::className(), ['user_group_id' => 'id']);
    }
}