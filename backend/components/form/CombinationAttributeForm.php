<?php
namespace albertgeeca\shop\backend\components\form;

use yii\base\Model;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CombinationAttributeForm extends Model
{
    public $attribute_id;

    public $attribute_value_id;


    public function rules()
    {
        return [
            [['attribute_id', 'attribute_value_id'], 'safe'],
        ];
    }


}