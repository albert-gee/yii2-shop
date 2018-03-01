<?php
namespace sointula\shop\backend\components\form;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * This model is used for adding images to country model
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CountryImageForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;

    public function rules()
    {
        return [
            [['image'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {

            $dir = \Yii::getAlias('@frontend/web/images/shop-product-country');

            if (!file_exists($dir)) {
                mkdir($dir);
            }

            if (!empty($this->image)) {

                $baseName = uniqid(hash('crc32', $this->image->name)) . '.' . $this->image->extension;

                $this->image->saveAs($dir . '/' . $baseName);

                return $baseName;
            }
        }
        return false;
    }
}