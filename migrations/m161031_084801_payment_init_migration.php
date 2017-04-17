<?php
use yii\db\Migration;
class m161031_084801_payment_init_migration extends Migration
{
    public function up()
    {
        $this->createTable('payment_method', [
            'id' => $this->primaryKey(),
        ]);
        $this->createTable('payment_method_translation', [
            'id' => $this->primaryKey(),
            'payment_method_id' => $this->integer(),
            'language_id' => $this->integer(),
            'title' => $this->string(),
            'description' => $this->text(),
            'image' => $this->string()
        ]);
        $this->addForeignKey('payment_method_translation_payment_method_id:payment_method_id',
            'payment_method_translation', 'payment_method_id', 'payment_method', 'id', 'cascade', 'cascade');
        $this->addForeignKey('payment_method_translation_language_id:language_id',
            'payment_method_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');
    }
    public function down()
    {
        $this->dropForeignKey('payment_method_translation_payment_method_id:payment_method_id',
            'payment_method_translation');
        $this->dropForeignKey('payment_method_translation_language_id:language_id',
            'payment_method_translation');
        $this->dropTable('payment_method_translation');
        $this->dropTable('payment_method');
    }
}