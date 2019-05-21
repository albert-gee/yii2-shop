<?php
namespace albertgeeca\shop\frontend\components\forms;
use yii\base\Model;

/**
 * This model is used in AdditionalProduct widget
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class AdditionalProductForm extends Model
{
    /**
     * @var integer
     */
    public $orderProductId;

    /**
     * @var integer
     */
    public $combinationId;

    /**
     * @var integer
     */
    public $additionalProductId;

    /**
     * Number of current additional product
     * @var integer
     */
    public $number;

    public function rules()
    {
        return [
            [['orderProductId', 'combinationId', 'additionalProductId', 'number'], 'integer']
        ];
    }
}