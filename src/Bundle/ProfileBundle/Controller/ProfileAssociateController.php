<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Manager\UserManagerInterface;
use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Provider\UserProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Route("/profile/{uuid}/associate", service="profile_bundle.profile_associate_controller")
 */
class ProfileAssociateController
{
    private $templating;
    private $manipulator;
    private $upManager;
    private $userManager;
    private $router;
    private $formFactory;
    private $userProvider;

    public function __construct(
        EngineInterface $templating,
        UserProfileManipulatorInterface $manipulator,
        UserProfileManagerInterface $upManager,
        UserManagerInterface $userManager,
        Router $router,
        FormFactory $formFactory,
        UserProviderInterface $userProvider
    ) {
        $this->templating = $templating;
        $this->manipulator = $manipulator;
        $this->upManager = $upManager;
        $this->userManager = $userManager;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->userProvider = $userProvider;
    }

    /**
     * @ParamConverter("profile", class="MMC\Profile\Component\Model\ProfileInterface")
     * @Route("", name="profile_bundle_show_associations_profile")
     * @Method({"GET"})
     */
    public function form(ProfileInterface $profile)
    {
        $users = $this->userManager->findUsers();

        $form = $this->formFactory->createBuilder()
            ->add('username', HiddenType::class)
            ->add('save', SubmitType::class)
            ->getForm()
        ;

        return $this->templating->renderResponse('MMCProfileBundle:Profile:associate.html.twig',
            [
                'users' => $users,
                'profile' => $profile,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @ParamConverter("profile", class="MMC\Profile\Component\Model\ProfileInterface")
     * @Route("", name="profile_bundle_associate_profile")
     * @Method({"POST"})
     */
    public function associate(Request $request, ProfileInterface $profile)
    {
        $username = $request->request->get('form')['username'];
        $user = $this->userProvider->findUserByUsername($username);
        $up = $this->manipulator->createUserProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }
}
