<?php

use yii\db\Migration;

class m160519_132817_params extends Migration
{
    public function safeUp()
    {
        $this->createTable('shop_param', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
        ]);
        $this->createTable('shop_param_translation', [
            'id' => $this->primaryKey(),
            'param_id' => $this->integer(),
            'language_id' => $this->integer(),
            'name' => $this->string(),
            'value' => $this->string(),
        ]);
        $this->addForeignKey('param_param_translation', 'shop_param_translation', 'param_id', 'shop_param', 'id', 'cascade', 'cascade');
        $this->addForeignKey('param_language', 'shop_param_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');
        $this->addForeignKey('param_product', 'shop_param', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');

    }

    public function safeDown()
    {
        $this->dropForeignKey('param_language', 'shop_param_translation');
        $this->dropForeignKey('param_param_translation', 'shop_param_translation');

        $this->dropTable('shop_param');
        $this->dropTable('shop_param_translation');
    }
}
