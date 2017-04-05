<?php
namespace xalberteinsteinx\shop\backend\components\form;
use yii\base\Model;
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class DeliveryForm extends Model
{
    public $text = '';
    public $subject = '';
    public function rules()
    {
        return [
            [['text', 'subject'], 'string']
        ];
    }
}