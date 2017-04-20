<?php

namespace MMC\Profile\Bundle\ProfileBundle\Form;

use MMC\Profile\Component\Provider\ProfileTypeProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    private $profileTypeProvider;

    public function __construct(ProfileTypeProviderInterface $profileTypeProvider)
    {
        $this->profileTypeProvider = $profileTypeProvider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $builder->add('roles', TextType::class, ['label' => 'roles']);
        $builder->add('type', ChoiceType::class, [
            'label' => 'type',
            'choices' => $this->profileTypeProvider->getTypes(),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('translation_domain', 'mmc_profile_bundle_profile');
    }

    public function getName()
    {
        return 'profile';
    }
}
