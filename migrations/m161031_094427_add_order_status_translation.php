<?php

use yii\db\Migration;

class m161031_094427_add_order_status_translation extends Migration
{
    public function up()
    {
        $language = \bl\multilang\entities\Language::find()->one();
        $this->insert('shop_order_status_translation', [
            'order_status_id' => '1',
            'language_id' => $language->id,
            'title' => 'Incomplete'
        ]);

        $this->insert('shop_order_status_translation', [
            'order_status_id' => '2',
            'language_id' => $language->id,
            'title' => 'Confirmed'
        ]);
    }

    public function down()
    {
        $this->delete('shop_order_status_translation', ['order_status_id' => '1',
            'language_id' => '2']);
        $this->delete('shop_order_status_translation', ['order_status_id' => '2',
            'language_id' => '2']);
    }

}
