<?php

namespace Okra\OkraBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Okra\OkraBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id; 
    
    /**
   * @ORM\Column(name="locale", type="string", length=2, nullable=true)
   *
   */
   protected $locale;

   public function getLocale()
   {
    return $this->locale;
   }

   /**
   * @param string $locale
   */
   public function setLocale($locale)
   {
    $this->locale = $locale;
   }    
    
    public function __construct()
    {
        parent::__construct();
    }    
}
