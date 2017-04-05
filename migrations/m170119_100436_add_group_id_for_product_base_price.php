<?php

use xalberteinsteinx\shop\common\components\user\models\UserGroup;
use xalberteinsteinx\shop\common\entities\CombinationPrice;
use xalberteinsteinx\shop\common\entities\Price;
use xalberteinsteinx\shop\common\entities\ProductPrice;
use yii\db\Migration;

class m170119_100436_add_group_id_for_product_base_price extends Migration
{
    public function up()
    {
        $this->createTable('shop_combination_price', [
            'id' => $this->primaryKey(),
            'combination_id' => $this->integer(),
            'user_group_id' => $this->integer(),
            'price_id' => $this->integer()
        ]);
        $this->addForeignKey('shop_combination_price_combination_id:shop_combination_id',
            'shop_combination_price', 'combination_id', 'shop_combination', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_combination_price_price_id:shop_price_id',
            'shop_combination_price', 'price_id', 'shop_price', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_combination_price_user_group_id:user_group_id',
            'shop_combination_price', 'user_group_id', 'user_group', 'id', 'cascade', 'cascade');

        $this->dropColumn('shop_price', 'inequality_sign');
        $this->dropColumn('shop_price', 'number');

        $this->migrateShopPrices();

        $this->addColumn('shop_product', 'price_id', $this->integer());
        $this->addForeignKey('shop_product_price_id:shop_price_id',
            'shop_product', 'price_id', 'shop_price', 'id', 'cascade', 'cascade');
        $this->createTable('shop_product_price', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'user_group_id' => $this->integer(),
            'price_id' => $this->integer(),
        ]);
        $this->addForeignKey('shop_product_price_product_id:shop_product_id',
            'shop_product_price', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_price_price_id:shop_price_id',
            'shop_product_price', 'price_id', 'shop_price', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_price_user_group_id:user_group_id',
            'shop_product_price', 'user_group_id', 'user_group', 'id', 'cascade', 'cascade');

        $this->migrateProductPrices();

        $this->dropForeignKey('discount_type_id:shop_price_discount_type_id', 'shop_product');
        $this->dropColumn('shop_product', 'price');
        $this->dropColumn('shop_product', 'discount');
        $this->dropColumn('shop_product', 'discount_type_id');

        $this->dropForeignKey('shop_price:shop_combination_id', 'shop_price');
        $this->dropColumn('shop_price', 'combination_id');

        $this->dropForeignKey('shop_price:user_group_id', 'shop_price');
        $this->dropColumn('shop_price', 'user_group_id');

    }

    public function down()
    {
        echo "m170119_100436_add_group_id_for_product_base_price cannot be reverted.\n";

        return false;
    }

    private function migrateShopPrices() {
        $shopPrices = Price::find()->all();
        foreach ($shopPrices as $price) {

            $newPrice = new Price();
            $newPrice->price = $price->price;
            $newPrice->discount = $price->discount;
            $newPrice->discount_type_id = $price->discount_type_id;
            $newPrice->save();

            $combinationPrice = new CombinationPrice();
            $combinationPrice->user_group_id = $price->user_group_id;
            $combinationPrice->combination_id = $price->combination_id;
            $combinationPrice->price_id = $newPrice->id;
            $combinationPrice->save();

        }
        $this->removeOldRecords();
    }

    private function removeOldRecords() {
        Price::deleteAll(['not', ['combination_id' => Null]]);
    }

    private function migrateProductPrices() {
        $products = \xalberteinsteinx\shop\common\entities\Product::find()->all();
        foreach ($products as $product) {
            if (!empty($product->price)) {
                $price = new Price();
                $price->price = $product->price;
                $price->discount = $product->discount;
                $price->discount_type_id = $product->discount_type_id;
                $price->save();

                $productPrice = new ProductPrice();
                $productPrice->product_id = $product->id;
                $productPrice->price_id = $price->id;
                $productPrice->user_group_id = UserGroup::USER_GROUP_ALL_USERS;
            }
        }
    }
}
