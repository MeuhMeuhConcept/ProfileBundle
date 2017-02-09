<?php

namespace MMC\Profile\Component\Model;

interface UserProfileInterface
{
    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive();

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return UserProfileInterface
     */
    public function setIsActive($isActive);

    /**
     * Get isOwner.
     *
     * @return bool
     */
    public function getIsOwner();

    /**
     * Set isOwner.
     *
     * @param bool $isOwner
     *
     * @return UserProfileInterface
     */
    public function setIsOwner($isOwner);

    /**
     * Get priority.
     *
     * @return int
     */
    public function getPriority();

    /**
     * Set priority.
     *
     * @param int $priority
     *
     * @return UserProfileInterface
     */
    public function setPriority($priority);
}
