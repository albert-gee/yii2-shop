<?php
namespace xalberteinsteinx\shop\common\components\user\models;

use bl\multilang\entities\Language;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_group_translation".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $user_group_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 *
 * @property Language $language
 * @property UserGroup $userGroup
 */
class UserGroupTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_group_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_group_id', 'language_id'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['user_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserGroup::className(), 'targetAttribute' => ['user_group_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'user_group_id' => Yii::t('shop', 'User Group ID'),
            'language_id' => Yii::t('shop', 'Language ID'),
            'title' => Yii::t('shop', 'Title'),
            'description' => Yii::t('shop', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroup()
    {
        return $this->hasOne(UserGroup::className(), ['id' => 'user_group_id']);
    }
}