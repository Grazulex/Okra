<?php

namespace Okra\OkraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Others
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Okra\OkraBundle\Entity\OthersRepository")
 */
class Others
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
     * @ORM\ManyToOne(targetEntity="Sessions")
     * @ORM\JoinColumn(name="id_session", referencedColumnName="id")
     */    
    private $idSession;        
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="date")
     */
    private $dateCreate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_type", type="integer")
     */
    private $idType;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;


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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Others
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set idType
     *
     * @param integer $idType
     * @return Others
     */
    public function setIdType($idType)
    {
        $this->idType = $idType;

        return $this;
    }

    /**
     * Get idType
     *
     * @return integer 
     */
    public function getIdType()
    {
        return $this->idType;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Others
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
     * Set comment
     *
     * @param string $comment
     * @return Others
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set idSession
     *
     * @param \Okra\OkraBundle\Entity\Sessions $idSession
     * @return Others
     */
    public function setIdSession(\Okra\OkraBundle\Entity\Sessions $idSession = null)
    {
        $this->idSession = $idSession;

        return $this;
    }

    /**
     * Get idSession
     *
     * @return \Okra\OkraBundle\Entity\Sessions 
     */
    public function getIdSession()
    {
        return $this->idSession;
    }
}
