# Yii2-shop module

Powerful E-commerce module for Yii2 framework.

## Installation
------------

### 1. Add extension ti your project:

Run command
```
composer require albert-sointula/yii2-shop
```

or add
```json
"albert-sointula/yii2-shop": "*"
```

to the require section of your composer.json.

### 2. You should configure "authManager" component in common configuration file:
```        
    'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
```

### 3. Set "Yii2-multi-lang" component in backend configuration file:
```
'components' => [

    'urlManager' => [
                'class' => bl\multilang\MultiLangUrlManager::className(),
                'baseUrl' => '/admin',
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'rules' => [
                ],
            ],
            'urlManagerFrontend' => [
                'class' => bl\multilang\MultiLangUrlManager::className(),
                'baseUrl' => '/',
                'showScriptName' => false,
                'enablePrettyUrl' => true,
                'enableDefaultLanguageUrlCode' => false,
                'rules' => [
                    [
                        'class' => bl\articles\UrlRule::className()
                    ],
                    [
                        'class' => xalberteinsteinx\shop\UrlRule::className(),
                        'prefix' => 'shop'
                    ],
                ]
            ],
]
```

### 4. Set "Yii2-locale" extension in frontend configuration file:
```
'components' => [
    'urlManager' => [
                'class' => bl\locale\UrlManager::className(),
                'baseUrl' => '/',
                'showScriptName' => false,
                'detectInSession' => false,
                'detectInCookie' => false,
                'enablePrettyUrl' => true,
                'languageProvider' => [
                    'class' => bl\locale\provider\DbLanguageProvider::className(),
                    'db' => 'db',
                    'table' => 'language',
                    'localeField' => 'lang_id',
                    'languageCondition' => ['active' => true],
                ],
                'lowerCase' => true,
                'useShortSyntax' => false,
                'languageKey' => 'language',
                'showDefault' => false,
                'rules' => [
                    
                    [
                        'class' => sointula\shop\UrlRule::className(),
                        'prefix' => 'shop'
                    ],
                    [
                        'class' => bl\seo\UniqueUrlRule::class,
                        'destination' => 'cart',
                        'duplicate' => [
                            'cart/cart/show',
                            'cart/cart'
                        ]
                    ],
                ]
            ],
]

```

### 5. Set Dektrium-user module:
in frontend config:
```
 'modules' => [
    ...
    
    'user' => [
                'modelMap' => [
                    'RegistrationForm' => sointula\shop\common\components\user\models\RegistrationForm::className(),
                    'RecoveryForm' => sointula\shop\common\components\user\models\RecoveryForm::className(),
                    'LoginForm' => sointula\shop\common\components\user\models\LoginForm::className(),
                    'SettingsForm' => sointula\shop\common\components\user\models\SettingsForm::className(),
                ],
                'controllerMap' => [
                    'registration' => sointula\shop\frontend\components\user\controllers\RegistrationController::className(),
                    'settings' => sointula\shop\frontend\components\user\controllers\SettingsController::className(),
                    'security' => sointula\shop\frontend\components\user\controllers\SecurityController::className(),
                    'recovery' => sointula\shop\frontend\components\user\controllers\RecoveryController::className()
                ],
                'as frontend' => dektrium\user\filters\FrontendFilter::className(),
                'enableFlashMessages' => false
            ],
    
    ...
 
 ],
 
 'components' => [
 
    ...
    'user' => [
                'identityClass' => sointula\shop\common\components\user\models\User::className(),
                'enableAutoLogin' => true,
                'identityCookie' => [
                    'name'     => '_frontendIdentity',
                    'path'     => '/',
                    'httpOnly' => true,
                ],
                'on afterLogin' => function() {
                    if (Yii::$app->cart->saveToDataBase) Yii::$app->cart->transportSessionDataToDB();
                },
                'on afterConfirm' => function() {
                    if (Yii::$app->cart->saveToDataBase) Yii::$app->cart->transportSessionDataToDB();
                },
            ],
    ...
    ],
```

in backend config:

```
'modules' => [

    ...
    'user' => [
                'enableRegistration' => false,
                'enableConfirmation' => false,
                'admins' => ['admin'],
                'adminPermission' => 'rbacManager',
                'controllerMap' => [
                    'admin' => sointula\shop\backend\components\user\controllers\AdminController::className(),
                    'security' => sointula\shop\frontend\components\user\controllers\SecurityController::className()
                ],
                'as backend' => [
                    'class' => 'dektrium\user\filters\BackendFilter',
                    'only' => ['register'], // Block View Register Backend
                ],
            ],
    ...
],

'components' => [

    ...
    'user' => [
                'identityClass' => dektrium\user\models\User::className(),
                'enableAutoLogin' => true,
                'returnUrl' => '/',
                'identityCookie' => [
                    'name'     => '_backendIdentity',
                    'path'     => '/admin',
                    'httpOnly' => true,
                ],
            ],
    ...
    
    'view' => [
                'theme' => [
                    'basePath' => '@backend/themes/' . $params['themeName'],
                    'baseUrl' => '@web/themes/' . $params['themeName'],
                    'pathMap' => [
                        '@dektrium/user/views' => '@vendor/sointula/yii2-shop/backend/views/user',
                    ],
                ],
            ],
],

```

in common configuration file:

```
'modules' => [

        'user' => [
            'class' => dektrium\user\Module::className(),
            'modelMap' => [
                'Profile' => sointula\shop\common\components\user\models\Profile::className(),
                'User' => sointula\shop\common\components\user\models\User::className()
            ],
        ],
```

### 6. Apply migrations:

```
- php yii migrate --migrationPath=@vendor/dektrium/yii2-user/migrations
- php yii migrate --migrationPath=@yii/rbac/migrations
- php yii migrate --migrationPath=@vendor/black-lamp/yii2-multi-lang/migration
- php yii migrate --migrationPath=@vendor/black-lamp/yii2-seo/migrations
- php yii migrate --migrationPath=@vendor/black-lamp/blcms-staticpage/migrations
- php yii migrate --migrationPath=@vendor/black-lamp/yii2-email-templates/src/migrations
- php yii migrate --migrationPath=@vendor/albert-sointula/yii2-shop/migrations

```

### 7. Add configuration for Imagable module in common configuration file:

```
        'shop_imagable' => [
            'class' => bl\imagable\Imagable::className(),
            'imageClass' => \bl\imagable\instances\CreateImageImagine::className(),
            'nameClass' => bl\imagable\name\CRC32Name::className(),
            'imagesPath' => '@frontend/web/images',
            'categories' => [
                'origin' => false,
                'category' => [
                    'shop-product' => [
                        'origin' => false,
                        'size' => [
                            'big' => [
                                'width' => 1500,
                                'height' => 500
                            ],
                            'thumb' => [
                                'width' => 500,
                                'height' => 500,
                            ],
                            'small' => [
                                'width' => 150,
                                'height' => 150
                            ]
                        ]
                    ],
                    'shop-vendors' => [
                        'origin' => false,
                        'size' => [
                            'big' => [
                                'width' => 1500,
                                'height' => 500
                            ],
                            'thumb' => [
                                'width' => 320,
                                'height' => 240,
                            ],
                            'small' => [
                                'width' => 150,
                                'height' => 150
                            ]
                        ]
                    ],
                    'delivery' => [
                        'origin' => false,
                        'size' => [
                            'big' => [
                                'width' => 1500,
                                'height' => 500
                            ],
                            'thumb' => [
                                'width' => 500,
                                'height' => 500,
                            ],
                            'small' => [
                                'width' => 150,
                                'height' => 150
                            ]
                        ]
                    ],
                    'shop-category/cover' => [
                        'origin' => false,
                        'size' => [
                            'big' => [
                                'width' => 1500,
                                'height' => 500
                            ],
                            'thumb' => [
                                'width' => 500,
                                'height' => 500,
                            ],
                            'small' => [
                                'width' => 150,
                                'height' => 150
                            ]
                        ]
                    ],
                    'shop-category/thumbnail' => [
                        'origin' => false,
                        'size' => [
                            'big' => [
                                'width' => 1500,
                                'height' => 500
                            ],
                            'thumb' => [
                                'width' => 500,
                                'height' => 500,
                            ],
                            'small' => [
                                'width' => 150,
                                'height' => 150
                            ]
                        ]
                    ],
                    'shop-category/menu_item' => [
                        'origin' => false,
                        'size' => [
                            'big' => [
                                'width' => 1500,
                                'height' => 500
                            ],
                            'thumb' => [
                                'width' => 500,
                                'height' => 500,
                            ],
                            'small' => [
                                'width' => 150,
                                'height' => 150
                            ]
                        ]
                    ],
                    'payment' => [
                        'origin' => false,
                        'size' => [
                            'big' => [
                                'width' => 1500,
                                'height' => 500
                            ],
                            'thumb' => [
                                'width' => 500,
                                'height' => 500,
                            ],
                            'small' => [
                                'width' => 150,
                                'height' => 150
                            ]
                        ]
                    ],
                ]
            ]
        ],
```

### 8. Add module to your backend config
```php
    'bootstrap' => [
        //'sointula\shop\backend\components\events\PartnersBootstrap',
        'sointula\shop\backend\components\events\ShopLogBootstrap',
        'sointula\shop\backend\components\events\CartBootstrap',
    ],
    'modules' => [
        'shop' => [
            'class' => 'sointula\shop\backend\Module',
            'enableCurrencyConversion' => true
        ]
    ]
```

### 9. Add module to your frontend config
```php
    'modules' => [
    	...
        'shop' => [
                    'class' => sointula\shop\frontend\Module::className(),
                    'enableCurrencyConversion' => true,
                    'partnerManagerEmail' => $params['partnerManagerEmail'],
                    'senderEmail' => $params['senderEmail'],
                    'showChildCategoriesProducts' => false
                ],
        ...
    ],
    
    'components' => [
        ...
        
        'partnerMailer' => [
                    'class' => yii\swiftmailer\Mailer::className(),
                    'useFileTransport' => false,
                    'messageConfig' => [
                        'charset' => 'UTF-8',
                    ],
                    'viewPath' => '@vendor/albert-sointula/yii2-shop/frontend/views/partner-request/mail',
                    'htmlLayout' => '@vendor/albert-sointula/yii2-shop/frontend/views/partner-request/mail/layout',
                    'transport' => [
                        'class' => 'Swift_SmtpTransport',
                        'username' => 'info@mail.com',
                        'password' => '55555555',
                        'host' => 'pop.mail.com',
                        'port' => '587',
                    ],
                ],
        ...
    ]

    'bootstrap' => [
        'sointula\shop\frontend\components\events\PartnersBootstrap',
        'sointula\shop\frontend\components\events\UserRegistrationBootstrap'
    ],
```

### 10. Set "Yii2-email-templates" module in frontend configuration file:
https://github.com/black-lamp/yii2-email-templates

### 11. Set Static-page module in backend configuration file:
```'modules' => [
        'seo' => [
                    'class' => 'bl\cms\seo\backend\Module'
                ],
],
```


## REQUIRES

- PHP-version: 7.0 or later
- PHP-extensions: file-info, imagick, intl


## Roles and its permissions:**

_attributeManager_
- addAttributeValue
- deleteAttribute
- saveAttribute
- viewAttributeList

_countryManager_
- saveCountry
- viewCountryList
- deleteCountry

_currencyManager_
- updateCurrency
- viewCurrencyList
- deleteCurrency

_deliveryMethodManager_
- saveDeliveryMethod
- viewDeliveryMethodList
- deleteDeliveryMethod

_orderManager_
- deleteOrder
- deleteOrderProduct
- viewOrder
- viewOrderList

_orderStatusManager_
- saveOrderStatus 
- viewOrderStatusList
- deleteOrderStatus

_productAvailabilityManager_
- saveProductAvailability
- viewProductAvailabilityList
- deleteProductAvailability

_productManager_
- createProduct
- createProductWithoutModeration
- deleteOwnProduct
- deleteProduct
- updateOwnProduct
- updateProduct
- viewCompleteProductList
- viewProductList

_productPartner_
- accessAdminPanel
- createProduct
- createProductWithoutModeration
- deleteOwnProduct
- deleteProduct
- updateOwnProduct
- updateProduct
- viewCompleteProductList
- viewProductList

_shopCategoryManager_
- saveShopCategory
- viewShopCategoryList

_vendorManager_
- saveVendor
- viewVendorList
- deleteVendor

_paymentManager_
- viewPaymentMethodList
- savePaymentMethod
- deletePaymentMethod

_shopAdministrator_
extends permissions from all managers. 


## WIDGETS

*Recommended products*

_Example:_
```
<?= \sointula\shop\widgets\RecommendedProducts::widget([
    'id' => $product->id,
]); ?>
```
Also you may use sointula\shop\widgets\assets\RecommendedProductsAsset in your view.


*Filtration widget*

To use the widget, you must have set up relations in the models. For example in model Product:
```php
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCountry()
    {
        return $this->hasOne(ProductCountry::className(), ['id' => 'country_id']);
    }
```


## LOGGING

Your application can record how many people watched a particular product.
To enable logging, you must add the following settings in the frontend configuration file:
```
public $log = [
        'enabled' => true,
        'maxProducts' => 10 // Max number of viewed products by one user.
    ];
```

In it, you specify the number of products, which is stored in the table shop_product_views for one user.
This value can be 'all', ie infinitely.

If the 'maxProducts' property value is "all", the "views" of Product object increases by one for a registered user once.
Otherwise it will increase by one each time when registered user views product.



## TRANSLATIONS

The module has translations on several languages. If there is not your language or if you would like change its on your own, you can configure it in backend or frontend configuration file:
```
'i18n' => [
            'translations' => [
                'shop' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    'sourceLanguage' => 'en-us',
                ]
            ],
        ],
```


## REPORTS

- "Class 'Imagick' not found"

If you use OpenServer with PHP 7, you must install Imagick extension like here http://open-server.ru/forum/viewtopic.php?f=4&t=2897&hilit=imagick


**Products displaying**

You can select one of two modes: showing products of current category and its children or only current category.
Use property $showChildCategoriesProducts in frontend Module class configuration.
migration:

**Logging**
This configuration is for Shop module and Cart module.

For enable logging add log component to your common configuration file:

```
'components' => [
        'log' => [
            'targets' => [
                [
                    'logTable' => 'shop_log',
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['info'],
                    'categories' => [
                        'afterCreateProduct', 'afterDeleteProduct', 'afterEditProduct',
                        'afterCreateCategory', 'afterEditCategory', 'afterDeleteCategory',
                    ],
                ],
                [
                    'logTable' => 'cart_log',
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['info'],
                    'categories' => [
                        'afterChangeOrderStatus'
                    ],
                ],
                [
                    'logTable' => 'user_log',
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['info'],
                    'categories' => [
                        'afterRegister', 'afterConfirm'
                    ],
                ],
            ],
        ],
```

Then apply migration, but only after you will configure your app.
The migration will create tables for log targets, which are listed in configuration.

```
php yii migrate --migrationPath=@yii/log/migrations/
```

In backend and frontend configuration of your module add
```
'enableLog' => true,
```

**Vendor list**
- /shop/vendor

**Partner requests email**
Create new templates 'partner-request-manager' and 'partner-request-partner': 
/admin/email-templates/default/list

You can use next variables: 
'{contact_person}', '{company_name}', '{website}', '{message}',
'{name}', '{surname}', '{patronymic}', '{info}'

Also create template 'partner-request-accept' without variables.

Information about new product by product partner to manager - template 'new-product-to-manager'.
You can use next variables: {productId}, {title}, {ownerId}, {ownerEmail}, {owner}, {link}
For sending information abount new product to partner which created this product, add template 
'new-product-to-partner' with variables {productId}, {title}, {ownerId}, {ownerEmail}, {owner}, {link}.

If product is moderated and status is 'accept' the mail 'accept-product-to-owner' will be sent.
You may use variables: {title}, {ownerEmail}, {owner}, {link}


## Links
Dektrium-User module: https://github.com/dektrium/yii2-user