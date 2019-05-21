<?php

use yii\db\Migration;

class m170207_143231_add_position_in_shop_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_param', 'position', $this->integer());
        $this->addPositionValues();
    }

    public function down()
    {
        $this->dropColumn('shop_param', 'position');
    }

    private function addPositionValues() {
        $products = \albertgeeca\shop\common\entities\Product::find()->with('params')->all();

        foreach ($products as $product) {
            $params = $product->params;

            if (!empty($params)) {

                $position = 1;

                /**
                 * @var $param \albertgeeca\shop\common\entities\Param
                 */
                foreach ($params as $param) {
                    $param->position = $position;
                    $position++;

                    if ($param->validate()) $param->save();
                }
            }
        }
    }
}
