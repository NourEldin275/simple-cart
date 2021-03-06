<?php
/**
 * Created by PhpStorm.
 * User: Nour Eldin
 * Date: 4/2/2017
 * Time: 9:35 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class CartItem
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="cart_item")
 */
class CartItem
{
    /**
     * @var
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="cart_items")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;


    /**
     * @var
     * @ORM\Column(type="string")
     */
    private $item_name;


    /**
     * @var
     *
     * @ORM\Column(type="float")
     */
    private $item_price;

    /**
     * @var
     * @ORM\Column(type="float")
     */
    private $item_total;

    /**
     * @var
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cart", inversedBy="items")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     */
    private $cart;


    /**
     * CartItem constructor.
     * @param Product $product
     * @param Cart $cart
     */
    public function __construct(Product $product, Cart $cart)
    {
        $this->setCart($cart);
        $this->setProduct($product);
        $this->setItemName($this->getProduct()->getProductName());
        $this->setItemPrice($this->getProduct()->getItemPrice());
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity and item_total
     *
     * @param integer $quantity
     *
     * @return CartItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->setItemTotal($this->getQuantity() * $this->getItemPrice());

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return CartItem
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set cart
     *
     * @param \AppBundle\Entity\Cart $cart
     *
     * @return CartItem
     */
    public function setCart(\AppBundle\Entity\Cart $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return \AppBundle\Entity\Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set itemPrice
     *
     * @param float $itemPrice
     *
     * @return CartItem
     */
    public function setItemPrice($itemPrice)
    {
        $this->item_price = $itemPrice;

        return $this;
    }

    /**
     * Get itemPrice
     *
     * @return float
     */
    public function getItemPrice()
    {
        return $this->item_price;
    }

    /**
     * Set itemTotal
     *
     * @param float $itemTotal
     *
     * @return CartItem
     */
    public function setItemTotal($itemTotal)
    {
        $this->item_total = $itemTotal;

        return $this;
    }

    /**
     * Get itemTotal
     *
     * @return float
     */
    public function getItemTotal()
    {
        return $this->item_total;
    }

    /**
     * Set itemName
     *
     * @param string $itemName
     *
     * @return CartItem
     */
    public function setItemName($itemName)
    {
        $this->item_name = $itemName;

        return $this;
    }

    /**
     * Get itemName
     *
     * @return string
     */
    public function getItemName()
    {
        return $this->item_name;
    }
}
