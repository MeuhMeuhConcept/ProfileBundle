<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use AppBundle\Form\ProfileTypeTest;
use MMC\Profile\Bundle\ProfileBundle\Form\ProfileType;
use MMC\Profile\Component\Manager\UserManagerInterface;
use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\Exception\InvalidProfileClassName;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Provider\ProfileTypeProviderInterface;
use MMC\Profile\Component\UuidGenerator\UuidGeneratorInterface;
use MMC\Profile\Component\Validator\ProfileTypeValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
    private $profileTypeProvider;
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
        ProfileTypeProviderInterface $profileTypeProvider,
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
        $this->profileTypeProvider = $profileTypeProvider;
        $this->profileClassname = $profileClassname;
    }

    /**
     * @Route("/createProfile", name="profile_bundle_createProfile")
     */
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
     * @Route("/profile/{uuid}", name="profile_bundle_seeProfile")
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
     * @Route("/profile/delete/{uuid}/{username}", name="profile_bundle_deleteProfile")
     * @ParamConverter("user", class="AppBundle:User")
     * @ParamConverter("profile", class="AppBundle:Profile")
     */
    public function deleteAction(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->removeProfileForUser($user, $profile);

        dump($up);

        $this->upManager->removeUserProfile($up);
        $this->upManager->flush();

        $users = $this->userManager->findUsers();
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->templating->renderResponse('AppBundle:Default:index.html.twig',
            ['user' => $user, 'users' => $users]);
    }

    /**
     * @Route("/profile/active/{username}/{uuid}", name="profile_bundle_activeProfile")
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function activeAction(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->setActiveProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        $users = $this->userManager->findUsers();
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->templating->renderResponse('AppBundle:Default:index.html.twig',
            ['user' => $user, 'users' => $users]);
    }

    /**
     * @Route("/profile/setPriority/{username}/{uuid}", name="profile_bundle_setPriorityProfile")
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function setPriorityAction(ProfileInterface $profile, UserInterface $user)
    {
        foreach ($profile->getUserProfiles() as $userProfile) {
            if ($userProfile->getUser() == $user) {
                $up = $userProfile;
            }
        }

        $up->setPriority(5);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        $users = $this->userManager->findUsers();
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->templating->renderResponse('AppBundle:Default:index.html.twig',
            ['user' => $user, 'users' => $users]);
    }

    /**
     * @Route("/profile/associate/{username}/{uuid}", name="profile_bundle_associateProfile")
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function associateAction(ProfileInterface $profile, UserInterface $user)
    {
        $users = $this->userManager->findUsers();

        return $this->templating->renderResponse('AppBundle:Profile:associate.html.twig',
            ['profile' => $profile, 'users' => $users]);
    }

    /**
     * @Route("/profile/addAssociation/{username}/{uuid}", name="profile_bundle_addAssociation")
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function addAssociationAction(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->createUserProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();
        dump($up);

        $users = $this->userManager->findUsers();
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->templating->renderResponse('AppBundle:Default:index.html.twig',
            ['user' => $user, 'users' => $users]);
    }
}
