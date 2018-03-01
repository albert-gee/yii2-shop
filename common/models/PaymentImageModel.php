<?php
namespace sointula\shop\common\models;

use bl\imagable\helpers\base\BaseFileHelper;
use Exception;
use yii\base\Model;
use yii\web\UploadedFile;
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $imageFile
 */
class PaymentImageModel extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFile;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imageFile'],  'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }
    public function upload()
    {
        if ($this->validate()) {
            $imagable = \Yii::$app->shop_imagable;
            $dir = $imagable->imagesPath . '/payment/';
            if (!empty($this->imageFile)) {
                if (!file_exists($dir)) BaseFileHelper::createDirectory($dir);
                $newFile = $dir . $this->imageFile->name;
                if ($this->imageFile->saveAs($newFile)) {
                    $image_name = $imagable->create('payment', $newFile);
                    unlink($newFile);
                    return $image_name;
                }
                else throw new Exception('Image saving failed.');
            }
        }
        return false;
    }
}