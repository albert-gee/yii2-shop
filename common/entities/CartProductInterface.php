<?php
namespace sointula\shop\common\entities;

/**
 * All objects that can be added to the cart must implement this interface
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 */

interface CartProductInterface
{
    /**
     * Returns the price for the cart item
     * @return integer
     */
    public function getPrice();
    /**
     * Returns the label for the cart item (displayed in cart etc)
     * @return string
     */
    public function getTitle();
    /**
     * Returns unique id to associate cart item with product
     * @return string
     */
    public function getId();
}