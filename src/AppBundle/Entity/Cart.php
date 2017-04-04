<?php
/**
 * Created by PhpStorm.
 * User: Nour Eldin
 * Date: 4/2/2017
 * Time: 9:50 PM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Cart
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="cart")
 */
class Cart
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CartItem", mappedBy="cart")
     */
    private $items;

    /**
     * @var
     * @ORM\Column(type="float", options={"default": 0})
     */
    private $subtotal;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set subtotal
     *
     * @param float $subtotal
     *
     * @return Cart
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Add item
     *
     * @param \AppBundle\Entity\CartItem $item
     *
     * @return Cart
     */
    public function addItem(\AppBundle\Entity\CartItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \AppBundle\Entity\CartItem $item
     */
    public function removeItem(\AppBundle\Entity\CartItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
