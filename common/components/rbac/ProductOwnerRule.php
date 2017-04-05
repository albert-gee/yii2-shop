<?php
namespace xalberteinsteinx\shop\common\components\rbac;
use xalberteinsteinx\shop\common\entities\Product;
use yii\rbac\Rule;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ProductOwnerRule extends Rule
{
    public $name = 'isProductOwner';

    /**
     * @param string|integer $userId the user ID.
     * @param Product $productOwner Id of product's owner
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean
     */
    public function execute($userId, $productOwner, $params)
    {
        if (\Yii::$app->user->isGuest) return false;
        else return array_key_exists('productOwner', $params) ? $params['productOwner'] == $userId : false;
    }
}

