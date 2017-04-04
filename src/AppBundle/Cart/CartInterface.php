<?php
/**
 * Created by PhpStorm.
 * User: Nour Eldin
 * Date: 4/4/2017
 * Time: 11:40 PM
 */

namespace AppBundle\Cart;


use AppBundle\Entity\CartItem;

interface CartInterface
{
    /**
     * @return mixed
     */
    public function clearCart();

    /**
     * @param CartItem $item
     * @param $quantity
     * @return mixed
     */
    public function addItem(CartItem $item, $quantity);

    /**
     * @param CartItem $item
     * @return mixed
     */
    public function removeItem(CartItem $item);

    /**
     * @return mixed
     */
    public function updateCartItems();

    /**
     * @return mixed
     */
    public function updateCartSubtotal();
}