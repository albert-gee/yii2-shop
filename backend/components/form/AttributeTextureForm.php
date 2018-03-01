<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

namespace sointula\shop\backend\components\form;
use sointula\shop\common\entities\ShopAttributeValueColorTexture;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AttributeTextureForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $color;
    public $title;

    public function rules()
    {
        return [
            [['color', 'title'], 'string'],
            [['imageFile'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'imageFile' => Yii::t('shop', 'Texture')
        ];
    }

    /**
     * @return bool|string
     */
    public function upload()
    {
        if (!empty($this->imageFile)) {

            $dir = Yii::getAlias('@frontend/web/images/shop/attribute-texture/');
            if ($this->validate()) {
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $fileName = self::generateTextureName();
                $this->imageFile->saveAs($dir . $fileName . '.' . $this->imageFile->extension);
                return $fileName . '.' . $this->imageFile->extension;
            }
        }
        return false;
    }

    public static function generateTextureName() {

        $generatedName = uniqid('texture-');
        return $generatedName;
    }

}