<?php
namespace xalberteinsteinx\shop\common\entities;

use yii\db\ActiveRecord;
use bl\multilang\entities\Language;

/**
 * This is the model class for table "shop_product_file_translation".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $product_file_id
 * @property integer $language_id
 * @property string $type
 * @property string $description
 *
 * @property Language $language
 * @property ProductFile $productFile
 */
class ProductFileTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_file_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_file_id', 'language_id'], 'integer'],
            [['type', 'description'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['product_file_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductFile::className(), 'targetAttribute' => ['product_file_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_file_id' => 'Product File ID',
            'language_id' => 'Language ID',
            'type' => 'Type',
            'description' => 'Description',
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
    public function getProductFile()
    {
        return $this->hasOne(ProductFile::className(), ['id' => 'product_file_id']);
    }
}