<?php

namespace sointula\shop\backend\components;

use bl\imagable\helpers\FileHelper;
use bl\imagable\interfaces\CreateImageInterface;
use yii\base\Object;

/**
 * Description of CreateImageImagine
 * @author RuslanSaiko
 */
class CreateImageImagine extends Object implements CreateImageInterface
{

    private $imagine;

    /**
     *
     * @var \Imagine\Image\ManipulatorInterface
     */
    private $openImage = null;

    public function init()
    {
        $this->imagine = new \Imagine\Gd\Imagine();
        return parent::init();
    }

    public function save($saveTo)
    {
        $this->openImage->save($saveTo);
    }

    public function thumbnail($pathToImage, $width, $height)
    {
        try {
            $this->openImage = $this->imagine->open(FileHelper::normalizePath($pathToImage))->thumbnail(new \Imagine\Image\Box($width,
                $height));
        } catch (\Exception $ex) {
            \Codeception\Util\Debug::debug($ex->getMessage());
        }
    }
}
