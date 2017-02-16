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

    /**
     * Get the profile type.
     *
     * @return string
     */
    public function getType();

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return ProfileInterface
     */
    public function setType($type);

    /**
     * Initialize userProfiles as ArrayCollection if null.
     */
    public function initializeUserProfile();

    /**
     * Initialize roles as ArrayCollection if null.
     */
    public function initializeRoles();
}
