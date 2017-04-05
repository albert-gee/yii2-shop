<?php

namespace xalberteinsteinx\shop\backend\components\form;
use xalberteinsteinx\shop\common\entities\Product;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use bl\imagable\Imagable;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

class ProductImageForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $image;
    public $link;
    public $alt1;
    public $alt2;

    public $extension = '.jpg';

    public function rules()
    {
        return [
            [['image'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize'=>'3000000'],
            [['link', 'alt1', 'alt2'], 'string', 'skipOnEmpty' => true]
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $imagable = \Yii::$app->shop_imagable;
            $dir = $imagable->imagesPath . '/shop-product/';

            if (!empty($this->image)) {
                if (!file_exists($dir)) BaseFileHelper::createDirectory($dir);
                $newFile = $dir . mt_rand() . $this->image->name;

                if ($this->image->saveAs($newFile)) {
                    $image_name = $imagable->create('shop-product', $newFile);

                    unlink($newFile);
                    return $image_name;
                }
                else throw new Exception('Image saving failed.');

            }
        }
        return false;
    }

    public function copy($link) {

        $imagable = \Yii::$app->shop_imagable;
        $dir = $imagable->imagesPath . '/shop-product/';

        if (exif_imagetype($link) == IMAGETYPE_JPEG || exif_imagetype($link) == IMAGETYPE_PNG) {

            if (!empty($link)) {

                $baseName = Product::generateImageName($link);

                if (!file_exists($dir)) mkdir($dir);

                $newFile = $dir . $baseName . $this->extension;
                if (copy($link, $newFile)) {
                    $image_name = $imagable->create('shop-product', $newFile);
                    unlink($newFile);
                    return $image_name;
                }
            }
        }
        return false;
    }
}