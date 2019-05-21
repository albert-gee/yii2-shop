<?php

use yii\db\Expression;
use yii\db\Migration;

class m170123_165408_add_update_time_to_combination_tables extends Migration
{
    public function up()
    {
        $this->addColumn('shop_combination', 'creation_time', $this->dateTime());
        $this->addColumn('shop_combination', 'update_time', $this->dateTime());

        $this->addColumn('shop_combination_attribute', 'creation_time', $this->dateTime());
        $this->addColumn('shop_combination_attribute', 'update_time', $this->dateTime());

        $this->addColumn('shop_combination_image', 'creation_time', $this->dateTime());
        $this->addColumn('shop_combination_image', 'update_time', $this->dateTime());

        $this->addColumn('shop_combination_price', 'creation_time', $this->dateTime());
        $this->addColumn('shop_combination_price', 'update_time', $this->dateTime());

        $this->addColumn('shop_combination_translation', 'creation_time', $this->dateTime());
        $this->addColumn('shop_combination_translation', 'update_time', $this->dateTime());

        $this->setsTimeColumns();
    }

    public function down()
    {

        $this->dropColumn('shop_combination', 'creation_time');
        $this->dropColumn('shop_combination', 'update_time');

        $this->dropColumn('shop_combination_attribute', 'creation_time');
        $this->dropColumn('shop_combination_attribute', 'update_time');

        $this->dropColumn('shop_combination_image', 'creation_time');
        $this->dropColumn('shop_combination_image', 'update_time');

        $this->dropColumn('shop_combination_price', 'creation_time');
        $this->dropColumn('shop_combination_price', 'update_time');

        $this->dropColumn('shop_combination_translation', 'creation_time');
        $this->dropColumn('shop_combination_translation', 'update_time');
    }

    private function setsTimeColumns() {
        $now = new Expression('NOW()');

        $models = [
            '\albertgeeca\shop\common\entities\Combination', '\albertgeeca\shop\common\entities\CombinationAttribute',
            '\albertgeeca\shop\common\entities\CombinationImage', '\albertgeeca\shop\common\entities\CombinationPrice',
            '\albertgeeca\shop\common\entities\CombinationTranslation'
        ];

        foreach ($models as $model) {
            /**
             * @var $model \yii\db\ActiveRecord
             */
            $objects = $model::find()->all();
            foreach ($objects as $object) {
                /**
                 * @var $combination \albertgeeca\shop\common\entities\Combination
                 */
                $object->creation_time = $now;
                $object->update_time = $now;
                $object->save();
            }
        }

    }
}
