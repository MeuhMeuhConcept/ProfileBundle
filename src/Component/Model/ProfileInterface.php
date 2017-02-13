<?php

namespace MMC\Profile\Component\Model;

interface ProfileInterface
{
    /**
     * Get uuid.
     *
     * @return string
     */
    public function getUuid();

    /**
     * Set uuid.
     *
     * @param string $uuid
     *
     * @return ProfileInterface
     */
    public function setUuid($uuid);

    /**
     * Get roles.
     *
     * @return ArrayCollection
     */
    public function getRoles();

    /**
     * Add a role.
     *
     * @return ProfileInterface
     */
    public function addRole($role);

    /**
     * Remove a role.
     *
     * @return ProfileInterface
     */
    public function removeRole($role);
}
