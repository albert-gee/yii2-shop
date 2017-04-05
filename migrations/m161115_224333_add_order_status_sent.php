<?php

use yii\db\Migration;

class m161115_224333_add_order_status_sent extends Migration
{
    public function safeUp()
    {
        $this->insert('shop_order_status', [
            'color' => '#1ab394'
        ]);

        $orderStatus = \xalberteinsteinx\shop\common\entities\OrderStatus::find()->where([
            'color' => '#1ab394'
        ])->orderBy('id DESC')->one();

        $language = \bl\multilang\entities\Language::find()->one();
        $this->insert('shop_order_status_translation', [
            'order_status_id' => $orderStatus->id,
            'language_id' => $language->id,
            'title' => 'Sent'
        ]);
    }

    public function safeDown()
    {
        $orderStatus = \xalberteinsteinx\shop\common\entities\OrderStatus::find()->where([
            'color' => '#1ab394'
        ])->orderBy('id DESC')->one();

        $record = \xalberteinsteinx\shop\common\entities\OrderStatusTranslation::find()->where([
            'order_status_id' => $orderStatus->id,
        ])->one();
        $record->delete();

        $record = \xalberteinsteinx\shop\common\entities\OrderStatus::findOne($orderStatus->id);
        $record->delete();
    }

}
