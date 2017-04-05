<?php

use yii\db\Migration;

class m170217_040859_alter_shop_combination_translation_table extends Migration
{
    public function up()
    {
        $this->alterColumn('shop_combination_translation', 'description', $this->text());
    }

    public function down()
    {
        $this->alterColumn('shop_combination_translation', 'description', $this->string());
    }

}
