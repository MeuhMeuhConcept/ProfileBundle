<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use MMC\Profile\Component\Model\UserProfile as BaseUserProfile;

  /**
   * @ORM\Entity
   * @ORM\Table(name="user_profile")
   * @ORM\HasLifecycleCallbacks
   * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false)
   */
class UserProfile extends BaseUserProfile
{

  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

  /**
   * @ORM\ManyToOne(targetEntity="User", inversedBy="userProfiles", cascade={"persist"})
   * @ORM\JoinColumn(nullable=false)
   */
    protected $user;

  /**
   * @ORM\ManyToOne(targetEntity="Profile", inversedBy="userProfiles", cascade={"persist"})
   * @ORM\JoinColumn(nullable=false)
   */
    protected $profile;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isOwner;

    /**
     * @ORM\Column(type="integer")
     */
    protected $priority;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deleted_at;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created_at = new \DateTime("now");
    }

    public function getDeleted_at(){
      return $this->deleted_at;
    }
}
