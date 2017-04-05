<?php
namespace xalberteinsteinx\shop\frontend\widgets;

use xalberteinsteinx\shop\common\entities\Product;
use yii\base\Widget;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class SaleProducts extends Widget
{

    /**
     * @var string
     */
    public $view;

    public function init()
    {

    }

    public function run()
    {
        parent::run();
        $saleProducts = Product::find()
            ->where(['show' => true, 'sale' => true, 'status' => Product::STATUS_SUCCESS])
            ->orderBy(['update_time' => SORT_DESC])
            ->all();

        return $this->render('_products',
            [
                'products' => $saleProducts
            ]);

    }
}