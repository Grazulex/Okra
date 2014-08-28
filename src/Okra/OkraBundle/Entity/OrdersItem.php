<?php

namespace Okra\OkraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrdersItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Okra\OkraBundle\Entity\OrdersItemRepository")
 */
class OrdersItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Orders")
     * @ORM\JoinColumn(name="id_order", referencedColumnName="id")
     */
    private $idOrder;

    /**
     * @ORM\ManyToOne(targetEntity="Item", cascade={"persist"})
     * @ORM\JoinColumn(name="id_item", referencedColumnName="id")
     */
    private $idItem;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;


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
     * Set quantity
     *
     * @param integer $quantity
     * @return OrdersItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

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
     * Set price
     *
     * @param float $price
     * @return OrdersItem
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set idOrder
     *
     * @param \Okra\OkraBundle\Entity\Orders $idOrder
     * @return OrdersItem
     */
    public function setIdOrder(\Okra\OkraBundle\Entity\Orders $idOrder = null)
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    /**
     * Get idOrder
     *
     * @return \Okra\OkraBundle\Entity\Orders 
     */
    public function getIdOrder()
    {
        return $this->idOrder;
    }

    /**
     * Set idItem
     *
     * @param \Okra\OkraBundle\Entity\Item $idItem
     * @return OrdersItem
     */
    public function setIdItem(\Okra\OkraBundle\Entity\Item $idItem = null)
    {
        $this->idItem = $idItem;

        return $this;
    }

    /**
     * Get idItem
     *
     * @return \Okra\OkraBundle\Entity\Item 
     */
    public function getIdItem()
    {
        return $this->idItem;
    }
}
