<?php

namespace MMC\Profile\Component\Model;

use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

interface UserInterface extends SymfonyUserInterface
{
    /**
     * Set username.
     *
     * @return UserInterface
     */
    public function setUsername($username);

    /**
     * Get roles form the active profile.
     *
     * @return array
     */
    public function getRoles();
}
