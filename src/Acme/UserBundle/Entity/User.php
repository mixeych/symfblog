<?php
namespace Acme\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Article", mappedBy="user")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->articles = new ArrayCollection();
    }
    
    public function getArticles()
    {
        return $this->articles;
    }

}