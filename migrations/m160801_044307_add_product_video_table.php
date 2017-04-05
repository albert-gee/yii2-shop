<?php

use yii\db\Migration;

class m160801_044307_add_product_video_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_product_video', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'resource' => $this->string(),
            'file_name' => $this->string(),
        ]);

        $this->addForeignKey('shop_product_video:product_id', 'shop_product_video', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');

    }

    public function down()
    {
        $this->dropForeignKey('shop_product_video:product_id', 'shop_product_video');

        $this->dropTable('shop_product_video');
    }

}
