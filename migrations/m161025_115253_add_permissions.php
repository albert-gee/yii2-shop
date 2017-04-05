<?php

use yii\db\Migration;

class m161025_115253_add_permissions extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        /*Vendor controller*/
        $viewVendorList = $auth->createPermission('viewVendorList');
        $viewVendorList->description = 'View list of vendors';
        $auth->add($viewVendorList);

        $saveVendor = $auth->createPermission('saveVendor');
        $saveVendor->description = 'Create and edit vendor';
        $auth->add($saveVendor);

        $deleteVendor = $auth->createPermission('deleteVendor');
        $deleteVendor->description = 'Delete vendor';
        $auth->add($deleteVendor);

        $vendorManager = $auth->createRole('vendorManager');
        $vendorManager->description = 'Vendor manager';
        $auth->add($vendorManager);

        $auth->addChild($vendorManager, $viewVendorList);
        $auth->addChild($vendorManager, $saveVendor);
        $auth->addChild($vendorManager, $deleteVendor);


        /*Product availability controller*/
        $viewProductAvailabilityList = $auth->createPermission('viewProductAvailabilityList');
        $viewProductAvailabilityList->description = 'View list of product availabilities';
        $auth->add($viewProductAvailabilityList);

        $saveProductAvailability = $auth->createPermission('saveProductAvailability');
        $saveProductAvailability->description = 'Save product availability';
        $auth->add($saveProductAvailability);

        $deleteProductAvailability = $auth->createPermission('deleteProductAvailability');
        $deleteProductAvailability->description = 'Delete product availability';
        $auth->add($deleteProductAvailability);

        $productAvailabilityManager = $auth->createRole('productAvailabilityManager');
        $productAvailabilityManager->description = 'Product availability manager';
        $auth->add($productAvailabilityManager);

        $auth->addChild($productAvailabilityManager, $viewProductAvailabilityList);
        $auth->addChild($productAvailabilityManager, $saveProductAvailability);
        $auth->addChild($productAvailabilityManager, $deleteProductAvailability);


        /*PartnersController*/
        $viewPartnerRequest = $auth->createPermission('viewPartnerRequest');
        $viewPartnerRequest->description = 'View partner request';
        $auth->add($viewPartnerRequest);
        $deletePartnerRequest = $auth->createPermission('deletePartnerRequest');
        $deletePartnerRequest->description = 'Delete partner request';
        $auth->add($deletePartnerRequest);

        $moderationManager = $auth->getRole('moderationManager');
        $auth->addChild($moderationManager, $viewPartnerRequest);
        $auth->addChild($moderationManager, $deletePartnerRequest);


        /*OrderStatusController*/
        $viewOrderStatusList = $auth->createPermission('viewOrderStatusList');
        $viewOrderStatusList->description = 'View order status';
        $auth->add($viewOrderStatusList);
        $saveOrderStatus = $auth->createPermission('saveOrderStatus');
        $saveOrderStatus->description = 'Save order status';
        $auth->add($saveOrderStatus);
        $deleteOrderStatus = $auth->createPermission('deleteOrderStatus');
        $deleteOrderStatus->description = 'Delete order status';
        $auth->add($deleteOrderStatus);

        $orderStatusManager = $auth->createRole('orderStatusManager');
        $orderStatusManager->description = 'Order status manager';
        $auth->add($orderStatusManager);

        $auth->addChild($orderStatusManager, $viewOrderStatusList);
        $auth->addChild($orderStatusManager, $saveOrderStatus);
        $auth->addChild($orderStatusManager, $deleteOrderStatus);


        /*OrderController*/
        $viewOrderList = $auth->createPermission('viewOrderList');
        $viewOrderList->description = 'View list of orders';
        $auth->add($viewOrderList);
        $viewOrder = $auth->createPermission('viewOrder');
        $viewOrder->description = 'View order';
        $auth->add($viewOrder);
        $deleteOrder = $auth->createPermission('deleteOrder');
        $deleteOrder->description = 'Delete order';
        $auth->add($deleteOrder);
        $deleteOrderProduct = $auth->createPermission('deleteOrderProduct');
        $deleteOrderProduct->description = 'Delete order product';
        $auth->add($deleteOrderProduct);

        $orderManager = $auth->createRole('orderManager');
        $orderManager->description = 'Order manager';
        $auth->add($orderManager);

        $auth->addChild($orderManager, $viewOrderList);
        $auth->addChild($orderManager, $viewOrder);
        $auth->addChild($orderManager, $deleteOrder);
        $auth->addChild($orderManager, $deleteOrderProduct);


        /*FilterController*/
        $viewFilterList = $auth->createPermission('viewFilterList');
        $viewFilterList->description = 'View list of filters';
        $auth->add($viewFilterList);
        $saveFilter = $auth->createPermission('saveFilter');
        $saveFilter->description = 'Save filter';
        $auth->add($saveFilter);
        $deleteFilter = $auth->createPermission('deleteFilter');
        $deleteFilter->description = 'Delete filter';
        $auth->add($deleteFilter);

        $filterManager = $auth->createRole('filterManager');
        $filterManager->description = 'Filter manager';
        $auth->add($filterManager);

        $auth->addChild($filterManager, $viewFilterList);
        $auth->addChild($filterManager, $saveFilter);
        $auth->addChild($filterManager, $deleteFilter);


        /*DeliveryMethodController*/
        $viewDeliveryMethodList = $auth->createPermission('viewDeliveryMethodList');
        $viewDeliveryMethodList->description = 'View list of delivery methods';
        $auth->add($viewDeliveryMethodList);

        $saveDeliveryMethod = $auth->createPermission('saveDeliveryMethod');
        $saveDeliveryMethod->description = 'Save delivery method';
        $auth->add($saveDeliveryMethod);

        $deleteDeliveryMethod = $auth->createPermission('deleteDeliveryMethod');
        $deleteDeliveryMethod->description = 'Delete delivery method';
        $auth->add($deleteDeliveryMethod);

        $deliveryMethodManager = $auth->createRole('deliveryMethodManager');
        $deliveryMethodManager->description = 'Delivery method manager';
        $auth->add($deliveryMethodManager);

        $auth->addChild($deliveryMethodManager, $viewDeliveryMethodList);
        $auth->addChild($deliveryMethodManager, $saveDeliveryMethod);
        $auth->addChild($deliveryMethodManager, $deleteDeliveryMethod);


        /*CurrencyController*/
        $viewCurrencyList = $auth->createPermission('viewCurrencyList');
        $viewCurrencyList->description = 'View list of currencies';
        $auth->add($viewCurrencyList);

        $updateCurrency = $auth->createPermission('updateCurrency');
        $updateCurrency->description = 'Update currency';
        $auth->add($updateCurrency);

        $deleteCurrency = $auth->createPermission('deleteCurrency');
        $deleteCurrency->description = 'Delete currency';
        $auth->add($deleteCurrency);

        $currencyManager = $auth->createRole('currencyManager');
        $currencyManager->description = 'Currency manager';
        $auth->add($currencyManager);

        $auth->addChild($currencyManager, $viewCurrencyList);
        $auth->addChild($currencyManager, $updateCurrency);
        $auth->addChild($currencyManager, $deleteCurrency);


        /*CountryController*/
        $viewCountryList = $auth->createPermission('viewCountryList');
        $viewCountryList->description = 'View list of countries';
        $auth->add($viewCountryList);

        $saveCountry = $auth->createPermission('saveCountry');
        $saveCountry->description = 'Save country';
        $auth->add($saveCountry);

        $deleteCountry = $auth->createPermission('deleteCountry');
        $deleteCountry->description = 'Delete country';
        $auth->add($deleteCountry);

        $countryManager = $auth->createRole('countryManager');
        $countryManager->description = 'Country manager';
        $auth->add($countryManager);

        $auth->addChild($countryManager, $viewCountryList);
        $auth->addChild($countryManager, $saveCountry);
        $auth->addChild($countryManager, $deleteCountry);


        /*CategoryController*/
        $viewCategoryList = $auth->createPermission('viewShopCategoryList');
        $viewCategoryList->description = 'View list of categories';
        $auth->add($viewCategoryList);

        $saveCategory = $auth->createPermission('saveShopCategory');
        $saveCategory->description = 'Save category';
        $auth->add($saveCategory);

        $deleteCategory = $auth->createPermission('deleteShopCategory');
        $deleteCategory->description = 'Delete category';
        $auth->add($deleteCategory);

        $categoryManager = $auth->createRole('shopCategoryManager');
        $categoryManager->description = 'Category manager';
        $auth->add($categoryManager);

        $auth->addChild($categoryManager, $viewCategoryList);
        $auth->addChild($categoryManager, $saveCategory);
        $auth->addChild($categoryManager, $deleteCategory);


        /*AttributeController*/
        $viewAttributeList = $auth->createPermission('viewAttributeList');
        $viewAttributeList->description = 'View list of attributes';
        $auth->add($viewAttributeList);

        $saveAttribute = $auth->createPermission('saveAttribute');
        $saveAttribute->description = 'Save attribute';
        $auth->add($saveAttribute);

        $deleteAttribute = $auth->createPermission('deleteAttribute');
        $deleteAttribute->description = 'Delete attribute';
        $auth->add($deleteAttribute);

        $addAttributeValue = $auth->createPermission('addAttributeValue');
        $addAttributeValue->description = 'Add attribute value';
        $auth->add($addAttributeValue);

        $attributeManager = $auth->createRole('attributeManager');
        $attributeManager->description = 'Attribute manager';
        $auth->add($attributeManager);

        $auth->addChild($attributeManager, $viewAttributeList);
        $auth->addChild($attributeManager, $saveAttribute);
        $auth->addChild($attributeManager, $deleteAttribute);
        $auth->addChild($attributeManager, $addAttributeValue);

    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $viewPartnerRequest = $auth->getPermission('viewPartnerRequest');
        $deletePartnerRequest = $auth->getPermission('deletePartnerRequest');
        $moderationManager = $auth->getRole('moderationManager');
        $auth->removeChild($moderationManager, $viewPartnerRequest);
        $auth->removeChild($moderationManager, $deletePartnerRequest);
        $auth->remove($viewPartnerRequest);
        $auth->remove($deletePartnerRequest);

        $permissions = [
            'viewVendorList', 'saveVendor', 'deleteVendor', 'viewProductAvailabilityList', 'saveProductAvailability',
            'deleteProductAvailability', 'viewPartnerRequest', 'deletePartnerRequest', 'viewOrderStatusList',
            'saveOrderStatus', 'deleteOrderStatus', 'viewOrderList', 'viewOrder', 'deleteOrder', 'deleteOrderProduct',
            'viewFilterList', 'saveFilter', 'deleteFilter', 'viewDeliveryMethodList', 'saveDeliveryMethod',
            'deleteDeliveryMethod', 'viewCurrencyList', 'updateCurrency', 'deleteCurrency', 'viewCountryList',
            'saveCountry', 'deleteCountry', 'viewShopCategoryList', 'saveShopCategory', 'deleteShopCategory', 'viewAttributeList',
            'saveAttribute', 'deleteAttribute', 'addAttributeValue'
        ];
        $roles = [
            'vendorManager', 'productAvailabilityManager', 'orderStatusManager', 'orderManager',
            'filterManager', 'deliveryMethodManager', 'currencyManager', 'countryManager',
            'shopCategoryManager', 'attributeManager'
        ];

        foreach ($roles as $role) {
            $auth->removeChildren($role);
            $auth->remove($role);
        }

        foreach ($permissions as $item) {
            $permission = $auth->getPermission($item);
            $auth->remove($permission);
        }
    }

}
