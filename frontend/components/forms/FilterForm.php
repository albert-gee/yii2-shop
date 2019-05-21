<?php
namespace albertgeeca\shop\frontend\components\forms;

use yii\base\Model;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class FilterForm extends Model
{
    /**
     * @var integer
     */
    public $vendor_id;
    /**
     * @var integer
     */
    public $category_id;
    /**
     * @var integer
     */
    public $availability_id;

    /**
     * Returns the validation rules for attributes.
     */
    public function rules()
    {
        return [
            [['vendor_id', 'availability_id'], 'integer'],
        ];
    }
}