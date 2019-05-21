<?php
namespace albertgeeca\shop\backend\components\form;

use yii\base\Model;
use yii\base\Security;
use yii\web\UploadedFile;


/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CategoryImageForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $cover;
    /**
     * @var UploadedFile[]
     */
    public $thumbnail;
    /**
     * @var UploadedFile[]
     */
    public $menu_item;

    public function rules()
    {
        return [
            [['cover', 'thumbnail', 'menu_item'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @return array|bool
     */
    public function upload()
    {
        if ($this->validate()) {

            $image_name = [];

            if (!empty($this->cover)) {
                $image_name['cover'] = $this->generateImages('cover');
            }

            if (!empty($this->thumbnail)) {
                $image_name['thumbnail'] = $this->generateImages('thumbnail');
            }

            if (!empty($this->menu_item)) {
                $image_name['menu_item'] = $this->generateImages('menu_item');
            }
            return $image_name;
        } else {
            return false;
        }
    }

    /**
     * Generates images from uploaded image using Imagable component
     *
     * @param $type
     * @return mixed
     */
    private function generateImages($type) {
        $imagable = \Yii::$app->shop_imagable;

        $nameFile = (new Security())->generateRandomString();
        $this->$type->saveAs($imagable->imagesPath . '/' . $nameFile . '.' . $this->$type->extension);
        $image_name = $imagable->create('shop-category/' . $type,
            $imagable->imagesPath . '/' . $nameFile . '.' . $this->$type->extension);
        unlink($imagable->imagesPath . '/' . $nameFile . '.' . $this->$type->extension);

        return $image_name;
    }
}