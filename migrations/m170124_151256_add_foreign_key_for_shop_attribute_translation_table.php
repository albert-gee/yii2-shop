<?php

use xalberteinsteinx\shop\common\entities\ShopAttribute;
use xalberteinsteinx\shop\common\entities\ShopAttributeTranslation;
use yii\db\Migration;

class m170124_151256_add_foreign_key_for_shop_attribute_translation_table extends Migration
{
    public function up()
    {
        $records = $this->saveShopAttributeTranslationsInArray();
        $this->deleteAllShopAttributeTranslations();

        $this->addForeignKey('shop_attribute_translation:shop_attribute_attr_id',
            'shop_attribute_translation', 'attr_id', 'shop_attribute', 'id');

        if (is_array($records)) {
            foreach ($records as $record) {
                $attribute = ShopAttribute::findOne($record['attr_id']);

                if (!empty($attribute)) {
                    $newRecord = new ShopAttributeTranslation();
                    $newRecord->title =
                        $record['title'];
                    $newRecord->language_id = $record['language_id'];
                    $newRecord->attr_id = $record['attr_id'];
                    $newRecord->save();
                }
            }
        }
    }

    public function down()
    {
        $this->dropForeignKey('shop_attribute_translation:shop_attribute_attr_id',
            'shop_attribute_translation');
    }

    private function saveShopAttributeTranslationsInArray() {
        $translations = ShopAttributeTranslation::find()->asArray()->all();
        return $translations;
    }

    private function deleteAllShopAttributeTranslations() {
        ShopAttributeTranslation::deleteAll('id' < 9999999);
    }
}
