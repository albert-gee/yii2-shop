<?php
/**
 * @author Vyacheslav Nozhenko <vv.nojenko@gmail.com>
 */

namespace xalberteinsteinx\shop\backend\components\form;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use bl\imagable\Imagable;

class VendorImage extends Model
{
    /** @var UploadedFile */
    public $imageFile;

    private $_orig_image_name;
    private $_image_name;
    private $_category = 'shop-vendors';

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, png']
        ];
    }

    public function attributeLabels()
    {
        return [
            'imageFile' => Yii::t('shop', 'Upload image')
        ];
    }

    public function getImageName()
    {
        return $this->_image_name;
    }

    public function notEmpty()
    {
        return (!empty($this->imageFile));
    }

    public function Upload()
    {
        /** @var Imagable $image */
        $image = Yii::$app->shop_imagable;

        // save original
        $this->_orig_image_name = $this->imageFile->name;
        $this->imageFile->saveAs($image->imagesPath . $this->_orig_image_name);
        // create small, thumb & big
        $this->_image_name = $image->create($this->_category, $image->imagesPath . $this->_orig_image_name);
        // delete original
        if (file_exists($image->imagesPath . $this->_orig_image_name))
            unlink($image->imagesPath . $this->_orig_image_name);
    }

    public function Remove($image_name)
    {
        if (!empty($image_name)) {
            $dir = Yii::getAlias('@frontend/web');
            if (file_exists($dir . $this->getBig($image_name)))
                unlink($dir . $this->getBig($image_name));
            if (file_exists($dir . $this->getThumb($image_name)))
                unlink($dir . $this->getThumb($image_name));
            if (file_exists($dir . $this->getSmall($image_name)))
                unlink($dir . $this->getSmall($image_name));
        }
    }

    /**
     * @param string $size
     * @param string $name
     * @return string
     */
    public function getImage(string $size, string $name)
    {
        $image = \Yii::$app->shop_imagable->get($this->_category, $size, $name);
        $image = str_replace('\\', '/', $image);
        $image = str_replace(str_replace('\\', '/', \Yii::getAlias('@frontend') . '/web'), '', $image);
        return $image;

    }
    public function getBig($image_name) {
        return $this->getImage('big', $image_name);
    }

    public function getThumb($image_name) {
        return $this->getImage('thumb', $image_name);
    }

    public function getSmall($image_name) {
        return $this->getImage('small', $image_name);
    }
}