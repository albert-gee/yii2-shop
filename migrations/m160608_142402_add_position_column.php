<?php

use albertgeeca\shop\common\entities\Category;
use albertgeeca\shop\common\entities\Product;
use yii\db\Migration;

class m160608_142402_add_position_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_product', 'position', 'INT(5) AFTER id');

        $categories = Category::find()->all();
        foreach ($categories as $category) {
            $products = Product::find()
                ->where(['category_id' => $category->id])
                ->all();
            for ($i = 0; $i < count($products); $i++) {
                $this->execute('UPDATE shop_product SET position=' . ($i + 1) . ' WHERE id=' . $products[$i]->id . ' AND category_id=' . $category->id);
            }
        }

        $this->addColumn('shop_category', 'position', $this->integer());
        for ($i = 0; $i < count($categories); $i++) {
            $this->execute('UPDATE shop_category SET position=' . ($i + 1) . ' WHERE id=' . $categories[$i]->id);
        }

    }

    public function safeDown()
    {
        $this->dropColumn('shop_category', 'position');
        $this->dropColumn('shop_product', 'position');
    }
}
