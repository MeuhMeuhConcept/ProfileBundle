<?php

namespace MMC\Profile\Component\Manager;

use MMC\Profile\Component\Model\UserInterface;

interface UserManagerInterface
{
    /**
     * Persist a user.
     */
    public function saveUser(UserInterface $user);

    /**
     * Make a flush.
     */
    public function flush();

    /**
     * Get all users.
     */
    public function findUsers();
}
