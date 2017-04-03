<?php
/**
 * Created by PhpStorm.
 * User: Nour Eldin
 * Date: 4/1/2017
 * Time: 12:20 PM
 */

namespace AppBundle\Cart;


use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class CartManager
 * @package AppBundle\Cart
 */
class CartManager implements \Serializable
{
    private $product_repository;
    private $session;

    /**
     * @var array Is an associative array of items with product id as key to the item. Each item is an associative array
     * containing the following: "product", "quantity", "item_price", "item_total"; where "product" is the object of
     * Product entity.
     */
    private $items = array();

    private $cart_subtotal;

    /**
     * CartManager constructor.
     * @param EntityManager $entityManager
     * @param Session $session
     */
    public function __construct(EntityManager $entityManager, Session $session)
    {
        $this->product_repository = $entityManager->getRepository('AppBundle:Product');
        $this->session = $session;

        if( !$this->session->isStarted() ){
            $this->session->start();
        }
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {

        return serialize($this->items);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {

//        list(
//            $this->items
//            ) = unserialize($serialized);

        $this->items = unserialize($serialized);
    }

    /**
     * @return mixed
     */
    public function getCartSubtotal(){

        return $this->session->get('sub_total');
    }

    /**
     * @param $subTotal
     * @return CartManager
     */
    public function setCartSubtotal($subTotal){

        $this->session->set('sub_total', $subTotal);
        return $this;
    }

    /**
     * Clears the cart session.
     */
    public function clearCart(){
        $this->session->remove('items');
        $this->session->remove('sub_total');
    }


    /**
     * Retrieves items from session
     * @return array
     */
    public function getCartItems(){
        $this->unserialize($this->session->get('items'));

        return $this->items;
    }

    /**
     * Updates the items and sub_total session attributes with modified data.
     */
    private function updateCartSession(){

        $this->session->set('items', $this->serialize());

        $this->setCartSubtotal(0); // resetting cart sub total before recalculating it.

        foreach ( $this->items as $item ){
            $this->cart_subtotal += $item['item_total'];
        }

        $this->session->set('sub_total', $this->cart_subtotal);
    }


    /**
     * @param Product $product
     * @param $quantity
     */
    public function addItem(Product $product, $quantity){


        if ( $this->session->get('items') ){ // if session is set
            $this->unserialize($this->session->get('items'));
        }

        if ( !array_key_exists($product->getId(), $this->items) ){
            $this->items[$product->getId()] = array(
                'product' => $product,
                'quantity' => $quantity,
                'item_price' => $product->getItemPrice(),
                'item_total' => $product->getItemPrice() * $quantity,

            );
        }

        else{

            $this->items[$product->getId()]['quantity'] += $quantity;
            $this->items[$product->getId()]['item_total'] += $quantity * $this->items[$product->getId()]['item_price'];
        }

        $this->updateCartSession();
    }


    /**
     * @param Product $product
     */
    public function removeItem(Product $product){

        if ( $this->session->get('items') ){ // if session is set
            $this->unserialize($this->session->get('items'));
        }

        unset($this->items[$product->getId()]);

        $this->updateCartSession();
    }


    /**
     * @param array $items
     * Updates the CartManager $items and then invokes the upDateCartSession() method to update the items & subtotal.
     */
    public function updateCartItems( array $items){
        $this->items = $items;

        $this->updateCartSession();
    }
}