<?php
/**
 * Created by PhpStorm.
 * User: Nour Eldin
 * Date: 4/1/2017
 * Time: 12:20 PM
 */

namespace AppBundle\Cart;


use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItem;
use Doctrine\ORM\EntityManager;

/**
 * Class CartManager
 * @package AppBundle\Cart
 */
class CartManager implements CartInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Cart This will hold the cart object of the current user. But in this simple application, it will always
     * hold the cart with id = 1
     */
    private $cart;

    /**
     * CartManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;

        //This would call the security.token_storage service to get the current user then get his cart.
        // But in this is simple application, it will always get the first and only cart in the database
        $this->cart = $this->em->getRepository('AppBundle:Cart')->find(1);

    }


    /**
     * Clears the cart session.
     * @return void
     */
    public function clearCart(){

        $items = $this->cart->getItems();

        $this->cart->setSubtotal(0);
        foreach ($items as $item){
            $this->em->remove($item);
        }
        $this->em->flush();
    }




    /**
     * Updates the cart's subtotal.
     * @return void
     */
    public function updateCartSubtotal(){

        $this->cart->setSubtotal(0);
        foreach ( $this->cart->getItems() as $item ){
            $this->cart->setSubtotal( $this->cart->getSubtotal() + $item->getItemTotal() );
        }

        $this->em->persist($this->cart);
        $this->em->flush($this->cart);
    }


    /**
     * @param CartItem $item
     * @param $quantity
     * @return void
     */
    public function addItem(CartItem $item, $quantity){

        $item->setQuantity( $item->getQuantity() + $quantity );
        $this->em->persist($item);
        $this->em->flush();
        $this->updateCartSubtotal();
    }


    /**
     * @param CartItem $item
     * @return void
     */
    public function removeItem(CartItem $item){

        $this->cart->removeItem($item);
        $this->em->remove($item);
        $this->em->flush();

        $this->updateCartSubtotal();
    }


    /**
     * Updates cart with the modified items.
     * @return void
     */
    public function updateCartItems(){

        $this->updateCartSubtotal();
    }
}