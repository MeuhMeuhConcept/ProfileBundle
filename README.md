# ProfileBundle
Overlay of user layer for Symfony3 Project

## Installation
```bash
composer require meuhmeuhconcept/profile-bundle
```

## Configuration
In app/AppKernel.php, add following lines
```php
public function registerBundles()
{
    $bundles = [

        // ...

        new MMC\Profile\Bundle\ProfileBundle\MMCProfileBundle()

        // ...
    ];

    // ...
}
```

Add mmc profile configuration:
```yaml
# app/config/config.yml
    mmc_profile:
        profile_class: AppBundle\Entity\Profile
        userProfile_class: AppBundle\Entity\UserProfile
        user_class: AppBundle\Entity\User
```

Add mmc profile user route :
```yaml
# app/config/routing.yml
mmc_profile:
    resource: "@MMCProfileBundle/Resources/config/routing.yml"
```

Create User, Profile and UserProfile entities:
```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MMC\Profile\Bridge\MMCUserBundle\Model\User as BaseUser;
// or MMC\Profile\Bridge\FosUserBundle\Model\User as BaseUser; if you use FOS

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
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
     * @ORM\OneToMany(targetEntity="UserProfile", mappedBy="user")
     */
    protected $userProfiles;
}
```

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MMC\Profile\Component\Model\Profile as BaseProfile;

/**
 * @ORM\Entity
 * @ORM\Table(name="profile")
 */
class Profile extends BaseProfile
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="UserProfile", mappedBy="profile")
     */
    protected $userProfiles;

    /**
     * @ORM\Column(type="string")
     */
    protected $uuid;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;
}

```

```php
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
```
