<?php

namespace Okra\OkraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Okra\OkraBundle\Entity\OrdersRepository")
 */
class Orders
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
     * @var integer
     *
     * @ORM\Column(name="id_table", type="integer")
     */
    private $idTable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_order_manual", type="integer", nullable=true)
     */
    private $idOrderManual;
	
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	

    /**
     * @ORM\ManyToOne(targetEntity="Sessions")
     * @ORM\JoinColumn(name="id_session", referencedColumnName="id")
     */    
    private $idSession;    
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=true)
     */    
    private $idUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $dateCreate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_close", type="datetime", nullable=true)
     */
    private $dateClose;    

    /**
     * @var integer
     *
     * @ORM\Column(name="id_status", type="integer")
     */
    private $idStatus;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;


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
     * Set idTable
     *
     * @param integer $idTable
     * @return Orders
     */
    public function setIdTable($idTable)
    {
        $this->idTable = $idTable;

        return $this;
    }

    /**
     * Get idTable
     *
     * @return integer 
     */
    public function getIdTable()
    {
        return $this->idTable;
    }

    /**
     * Set idOrderManual
     *
     * @param integer $idOrderManual
     * @return Orders
     */
    public function setIdOrderManual($idOrderManual)
    {
        $this->idOrderManual = $idOrderManual;

        return $this;
    }

    /**
     * Get idOrderManual
     *
     * @return integer 
     */
    public function getIdOrderManual()
    {
        return $this->idOrderManual;
    }


    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Orders
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
     * Set idStatus
     *
     * @param integer $idStatus
     * @return Orders
     */
    public function setIdStatus($idStatus)
    {
        $this->idStatus = $idStatus;

        return $this;
    }

    /**
     * Get idStatus
     *
     * @return integer 
     */
    public function getIdStatus()
    {
        return $this->idStatus;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return Orders
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }
    
    

    /**
     * Set idSession
     *
     * @param \Okra\OkraBundle\Entity\Sessions $idSession
     * @return Orders
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

    /**
     * Set idUser
     *
     * @param \Okra\OkraBundle\Entity\User $idUser
     * @return Orders
     */
    public function setIdUser(\Okra\OkraBundle\Entity\User $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \Okra\OkraBundle\Entity\User 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set dateClose
     *
     * @param \DateTime $dateClose
     * @return Orders
     */
    public function setDateClose($dateClose)
    {
        $this->dateClose = $dateClose;

        return $this;
    }

    /**
     * Get dateClose
     *
     * @return \DateTime 
     */
    public function getDateClose()
    {
        return $this->dateClose;
    }
	
    /**
     * Set name
     *
     * @param string $name
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }	
}
