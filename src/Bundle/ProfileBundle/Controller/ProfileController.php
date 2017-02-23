<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use AppBundle\Form\ProfileTypeTest;
use MMC\Profile\Bundle\ProfileBundle\Form\PriorityType;
use MMC\Profile\Bundle\ProfileBundle\Form\ProfileType;
use MMC\Profile\Component\Manager\UserManagerInterface;
use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\Exception\InvalidProfileClassName;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\UuidGenerator\UuidGeneratorInterface;
use MMC\Profile\Component\Validator\ProfileTypeValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

class ProfileController
{
    private $templating;
    private $tokenStorage;
    private $manipulator;
    private $upManager;
    private $userManager;
    private $formFactory;
    private $router;
    private $uuidGenerator;
    private $profileTypeValidator;
    private $profileClassname;

    public function __construct(
        EngineInterface $templating,
        TokenStorage $tokenStorage,
        UserProfileManipulatorInterface $manipulator,
        UserProfileManagerInterface $upManager,
        UserManagerInterface $userManager,
        FormFactory $formFactory,
        Router $router,
        UuidGeneratorInterface $uuidGenerator,
        ProfileTypeValidator $profileTypeValidator,
        $profileClassname
        ) {
        if (!is_subclass_of($profileClassname, ProfileInterface::class)) {
            throw new InvalidProfileClassName();
        }

        $this->templating = $templating;
        $this->tokenStorage = $tokenStorage;
        $this->manipulator = $manipulator;
        $this->upManager = $upManager;
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->uuidGenerator = $uuidGenerator;
        $this->profileTypeValidator = $profileTypeValidator;
        $this->profileClassname = $profileClassname;
    }

    public function createAction(Request $request)
    {
        $profile = new $this->profileClassname();
        $profile->setUuid($this->uuidGenerator->generate());

        $form = $this->formFactory->create(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->profileTypeValidator->validate($profile->getType());

            $user = $this->tokenStorage->getToken()->getUser();
            $up = $this->manipulator->createUserProfile($user, $profile);

            $this->upManager->saveUserProfile($up);
            $this->upManager->flush();

            return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
        }

        $formTest = $this->formFactory->create(ProfileTypeTest::class, $profile);
        $formTest->handleRequest($request);

        if ($formTest->isSubmitted() && $formTest->isValid()) {
            $this->profileTypeValidator->validate($profile->getType());

            $user = $this->tokenStorage->getToken()->getUser();
            $up = $this->manipulator->createUserProfile($user, $profile);

            $this->upManager->saveUserProfile($up);
            $this->upManager->flush();

            return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
        }

        return $this->templating->renderResponse('AppBundle:Profile:create.html.twig',
            ['form' => $form->createView(), 'formTest' => $formTest->createView()]);
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function seeAction(ProfileInterface $profile, UserInterface $user)
    {
        foreach ($profile->getUserProfiles() as $userProfile) {
            if ($userProfile->getUser() == $user) {
                $up = $userProfile;
            }
        }

        return $this->templating->renderResponse('AppBundle:Profile:profile.html.twig',
            ['userProfile' => $up, 'user' => $user]);
    }

    /**
     * @ParamConverter("user", class="AppBundle:User")
     * @ParamConverter("profile", class="AppBundle:Profile")
     */
    public function deleteAction(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->removeProfileForUser($user, $profile);

        $this->upManager->removeUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function activeAction(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->setActiveProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function setPriorityAction(Request $request, ProfileInterface $profile, UserInterface $user)
    {
        foreach ($profile->getUserProfiles() as $userProfile) {
            if ($userProfile->getUser() == $user) {
                $up = $userProfile;
            }
        }

        $form = $this->formFactory->create(PriorityType::class, $up);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->upManager->saveUserProfile($up);
            $this->upManager->flush();

            return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
        }

        return $this->templating->renderResponse('AppBundle:Profile:priority.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     */
    public function associateAction(ProfileInterface $profile)
    {
        $users = $this->userManager->findUsers();

        return $this->templating->renderResponse('AppBundle:Profile:associate.html.twig',
            ['profile' => $profile, 'users' => $users]);
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function addAssociationAction(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->createUserProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }
}
