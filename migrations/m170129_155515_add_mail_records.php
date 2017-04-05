<?php

use bl\emailTemplates\models\entities\EmailTemplate;
use yii\db\Migration;

class m170129_155515_add_mail_records extends Migration
{
    public function up()
    {
        $languageId = \bl\multilang\entities\Language::getCurrent()->id;

        $this->insert('email_template', [
            'key' => 'new-order'
        ]);
        $this->insert('email_template', [
            'key' => 'order-success'
        ]);
        $this->insert('email_template_translation', [
            'template_id' => EmailTemplate::find()->where(['key' => 'new-order'])->one()->id,
            'language_id' => $languageId
        ]);
        $this->insert('email_template_translation', [
            'template_id' => EmailTemplate::find()->where(['key' => 'order-success'])->one()->id,
            'language_id' => $languageId
        ]);
    }

    public function down()
    {
        return false;
    }
}
