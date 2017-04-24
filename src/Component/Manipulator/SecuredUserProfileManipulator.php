<?php

namespace MMC\Profile\Component\Manipulator;

use MMC\Profile\Component\Manipulator\Exception\ManipulatorAccessDeniedHttpException;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecuredUserProfileManipulator implements UserProfileManipulatorInterface
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
        $up = [];
        foreach ($profile->getUserProfiles() as $userProfile) {
            if ($userProfile->getUser() == $user) {
                $up = $userProfile;
            }
        }

        if (!$this->authorizationChecker->isGranted('CAN_GET_USER_PROFILE', $up)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

        return $this->manipulator->getUserProfile($user, $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveProfile(UserInterface $user)
    {
        if (!$this->authorizationChecker->isGranted('CAN_GET_ACTIVE_PROFILE', $user)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

        return $this->manipulator->getActiveProfile($user);
    }

    /**
     * {@inheritdoc}
     */
    public function isOwner(UserInterface $user, ProfileInterface $profile)
    {
        $up = $this->getUserProfile($user, $profile);
        if (!$this->authorizationChecker->isGranted('CAN_GET_IS_OWNER_USERPROFILE', $up)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

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
        $association = false;
        if (!$creation) {
            $association = true;
            if (!$this->authorizationChecker->isGranted('CAN_ASSOCIATE_PROFILE', $profile)) {
                throw new ManipulatorAccessDeniedHttpException();
            }
        }

        return $this->manipulator->createUserProfile($user, $profile, $association);
    }

    /**
     * {@inheritdoc}
     */
    public function getOwners(ProfileInterface $profile)
    {
        if (!$this->authorizationChecker->isGranted('CAN_GET_OWNERS', $profile)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

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
        if (!$this->authorizationChecker->isGranted('CAN_DISSOCIATE_USERPROFILE', $up)) {
            throw new ManipulatorAccessDeniedHttpException();
        }

        return $this->manipulator->removeProfileForUser($user, $profile);
    }
}
