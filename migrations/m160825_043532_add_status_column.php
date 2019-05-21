<?php

use albertgeeca\shop\common\entities\Product;
use yii\db\Migration;

class m160825_043532_add_status_column extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'status', $this->integer()->defaultValue(1));

        $products = Product::find()->all();
        foreach ($products as $product) {
            $product->status = 10;
            $product->save();
        }
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'status');
    }
}
