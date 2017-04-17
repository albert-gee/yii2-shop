<?php
use yii\db\Migration;
class m161103_080058_remove_and_add_image_column extends Migration
{
    public function up()
    {
        $this->dropColumn('payment_method_translation', 'image');
        $this->addColumn('payment_method', 'image', $this->string());
    }
    public function down()
    {
        $this->addColumn('payment_method_translation', 'image', $this->string());
        $this->dropColumn('payment_method', 'image');
    }
}