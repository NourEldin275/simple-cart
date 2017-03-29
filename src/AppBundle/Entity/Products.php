<?php
/**
 * Created by PhpStorm.
 * User: Nour Eldin
 * Date: 3/29/2017
 * Time: 9:57 PM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Products
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Products {

    /**
     * @var
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="A Product must have a name!")
     */
    private $product_name;


    /**
     * @var
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="A Product must have price!")
     */
    private $item_price;


    /**
     * @var
     * @ORM\Column(type="integer", nullable=true)
     */
    private $items_in_stock;


    /**
     * @var
     * @ORM\ManyToOne(targetEntity="ProductCategories", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;



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
     * Set productName
     *
     * @param string $productName
     *
     * @return Products
     */
    public function setProductName($productName)
    {
        $this->product_name = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Set itemPrice
     *
     * @param float $itemPrice
     *
     * @return Products
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
     * Set itemsInStock
     *
     * @param integer $itemsInStock
     *
     * @return Products
     */
    public function setItemsInStock($itemsInStock)
    {
        $this->items_in_stock = $itemsInStock;

        return $this;
    }

    /**
     * Get itemsInStock
     *
     * @return integer
     */
    public function getItemsInStock()
    {
        return $this->items_in_stock;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\ProductCategories $category
     *
     * @return Products
     */
    public function setCategory(\AppBundle\Entity\ProductCategories $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\ProductCategories
     */
    public function getCategory()
    {
        return $this->category;
    }
}
