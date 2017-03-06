<?php

namespace MMC\Profile\Component\Manipulator;

use MMC\Profile\Component\Manipulator\Exception\ManipulatorAccessDeniedHttpException;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurizingUserProfileManipulator implements UserProfileManipulatorInterface
{
    private $manipulator;
    private $authorizationChecker;

    public function __construct(
        UserProfileManipulatorInterface $manipulator,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->manipulator = $manipulator;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserProfile(UserInterface $user, ProfileInterface $profile)
    {
        return $this->manipulator->getUserProfile($user, $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveProfile(UserInterface $user)
    {
        return $this->manipulator->getActiveProfile($user);
    }

    /**
     * {@inheritdoc}
     */
    public function isOwner(UserInterface $user, ProfileInterface $profile)
    {
        return $this->manipulator->isOwner($user, $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function setActiveProfile(UserInterface $user, ProfileInterface $profile)
    {
        $up = $this->getUserProfile($user, $profile);
        if (!$this->authorizationChecker->isGranted('CAN_ACTIVATE_USERPROFILE', $up)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

        return $this->manipulator->setActiveProfile($user, $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function setProfilePriority(UserInterface $user, ProfileInterface $profile)
    {
        $up = $this->getUserProfile($user, $profile);
        if (!$this->authorizationChecker->isGranted('CAN_SET_PRIORITY_USERPROFILE', $up)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

        return $this->manipulator->setProfilePriority($user, $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function createUserProfile(UserInterface $user, ProfileInterface $profile, $creation = false)
    {
        if (!$creation) {
            if (!$this->authorizationChecker->isGranted('CAN_ASSOCIATE_PROFILE', $profile)) {
                throw new ManipulatorAccessDeniedHttpException();
            }
        }

        return $this->manipulator->createUserProfile($user, $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function createProfileForUser(UserInterface $user)
    {
        return $this->manipulator->createProfileForUser($user, $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function getOwners(ProfileInterface $profile)
    {
        return $this->manipulator->getOwners($profile);
    }

    /**
     * {@inheritdoc}
     */
    public function promoteUserProfile(UserInterface $user, ProfileInterface $profile)
    {
        $up = $this->getUserProfile($user, $profile);
        if (!$this->authorizationChecker->isGranted('CAN_PROMOTE_USERPROFILE', $up)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

        return $this->manipulator->promoteUserProfile($user, $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function demoteUserProfile(UserInterface $user, ProfileInterface $profile)
    {
        $up = $this->getUserProfile($user, $profile);
        if (!$this->authorizationChecker->isGranted('CAN_DEMOTE_USERPROFILE', $up)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

        return $this->manipulator->demoteUserProfile($user, $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function removeProfileForUser(UserInterface $user, ProfileInterface $profile)
    {
        $up = $this->getUserProfile($user, $profile);
        if (!$this->authorizationChecker->isGranted('CAN_DELETE_USERPROFILE', $up)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

        return $this->manipulator->removeProfileForUser($user, $profile);
    }
}
