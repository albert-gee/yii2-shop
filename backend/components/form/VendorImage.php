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
    private $_image_extension = '.jpg';
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

    public function getImageName() {
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
        $this->_orig_image_name = $this->imageFile->baseName . $this->_image_extension;
        $this->imageFile->saveAs($image->imagesPath . $this->_orig_image_name);
        // create small, thumb & big
        $this->_image_name = $image->create($this->_category, $image->imagesPath . $this->_orig_image_name);
        // delete original
        if(file_exists($image->imagesPath . $this->_orig_image_name))
            unlink($image->imagesPath . $this->_orig_image_name);
    }

    public function Remove($image_name)
    {
        if(!empty($image_name)) {
            $dir = Yii::getAlias('@frontend/web');
            if(file_exists($dir . $this->getBig($image_name)))
                unlink($dir . $this->getBig($image_name));
            if(file_exists($dir . $this->getThumb($image_name)))
                unlink($dir . $this->getThumb($image_name));
            if(file_exists($dir . $this->getSmall($image_name)))
                unlink($dir . $this->getSmall($image_name));
        }
    }

    public function getBig($image_name) {
        return ('/images/' . $this->_category . '/' . $image_name . '-big' . $this->_image_extension);
    }

    public function getThumb($image_name) {
        return ('/images/' . $this->_category . '/' . $image_name . '-thumb' . $this->_image_extension);
    }

    public function getSmall($image_name) {
        return ('/images/' . $this->_category . '/' . $image_name . '-small' . $this->_image_extension);
    }
}