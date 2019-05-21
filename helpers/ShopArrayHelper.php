<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

namespace albertgeeca\shop\helpers;

/**
 * Removes duplicated elements from multidimensional array.
 *
 * Class ShopArrayHelper
 * @package albertgeeca\shop\helpers
 * @returns array
 */
class ShopArrayHelper
{
    public static function removeDuplicatedArrayElements($array) {
        $result = array_reduce($array, function($a, $b) {
            static $stored = [];

            $hash = md5(serialize($b));

            if (!in_array($hash, $stored)) {
                $stored[] = $hash;
                $a[] = $b;
            }

            return $a;
        }, []);

        return $result;
    }
}