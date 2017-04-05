<?php
namespace xalberteinsteinx\shop\frontend\widgets\models;

use yii\base\Model;

/**
 * This model is used in cart for adding or deleting additional products
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class AdditionalProductForm extends Model
{

    /**
     * @var integer
     */
    public $productId;

    /**
     * Number of current additional product
     * @var integer
     */
    public $number;

    public function rules()
    {
        return [
            [['productId', 'number'], 'integer']
        ];
    }
}