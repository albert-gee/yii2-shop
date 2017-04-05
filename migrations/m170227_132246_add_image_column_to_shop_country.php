<?php

use yii\db\Migration;

class m170227_132246_add_image_column_to_shop_country extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product_country', 'image', $this->string());
    }

    public function down()
    {
        $this->dropColumn('shop_product_country', 'image');
    }

}
