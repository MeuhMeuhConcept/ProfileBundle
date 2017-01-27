<?php

namespace MMC\Profile\Component\Model;

interface ProfileInterface
{
    /**
     * Get id.
     *
     * @return int
     */
    public function getId();

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
     * @return array
     */
    public function getRoles();

    /**
     * Set roles.
     *
     * @param array $roles
     *
     * @return ProfileInterface
     */
    public function setRoles($roles);
}
