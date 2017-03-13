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
        profile_class: Path\To\Your\Entity\Profile
        # (like AppBundle\Entity\Profile )
        userProfile_class: Path\To\Your\Entity\UserProfile
        user_class: Path\To\Your\Entity\User
```

Add mmc profile user route :
```yaml
# app/config/routing.yml
mmc_profile:
    resource: "@MMCProfileBundle/Controller"
    type: annotation
```

Create User, Profile and UserProfile entities:
```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MMC\Profile\Bridge\MMCUserBundle\Model\User as BaseUser;
// or MMC\Profile\Bridge\FosUserBundle\Model\User as BaseUser;
// if you use FOSUserBundle

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
}
```

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MMC\Profile\Component\Model\UserProfile as BaseUserProfile;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_profile")
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userProfiles")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="userProfiles")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $profile;
}
```

## Configuration

### Using SoftDeleteable

Allows to implicitly remove userProfiles by using StofDoctrineExtensionsBundle.

#### First you must install StofDoctrineExtensionsBundle:

See documentation: http://symfony.com/doc/current/bundles/StofDoctrineExtensionsBundle/index.html

#### Enable the softdeleteable filter:
See documentation: http://symfony.com/doc/current/bundles/StofDoctrineExtensionsBundle/index.html#enable-the-softdeleteable-filter

##### Add this elements in UserProfile entity:

```php
<?php

namespace AppBundle\Entity;

// ...
use Gedmo\Mapping\Annotation as Gedmo;
// ...

/**
 * // ...
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * // ...
 */
class UserProfile extends BaseUserProfile
{
    // ...
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    public function getDeletedAt(){
      return $this->deletedAt;
    }

    // ...
}
```

### Add createdAt field

Allows to store creation date of a userProfile.
You must add this elements in UserProfile entity:

```php
<?php

namespace AppBundle\Entity;

/**
 * // ...
 * @ORM\HasLifecycleCallbacks
 * // ...
 */
class UserProfile extends BaseUserProfile
{

    // ...

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime("now");
    }

    // ...
}
```
