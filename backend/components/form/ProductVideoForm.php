<?php

namespace albertgeeca\shop\backend\components\form;
use albertgeeca\shop\common\entities\Product;
use Yii;
use yii\base\Model;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

class ProductVideoForm extends Model
{

    public $file_name;
    public $resource;

    public function rules()
    {
        return [
            [['file_name'], 'file', 'skipOnEmpty' => true, 'extensions' => 'avi, mp4']
        ];
    }

    public function upload()
    {

        if ($this->validate()) {

            $dir = Yii::getAlias('@frontend/web/video');

            if (!file_exists($dir)) {
                mkdir($dir);
            }

            if (!empty($this->file_name)) {

                $baseName = Product::generateImageName($this->file_name->name) . '.' . $this->file_name->extension;

                $this->file_name->saveAs($dir . '/' . $baseName);

                return $baseName;
            }

        }
        else die(var_dump($this->errors));

        return false;
    }
    
}