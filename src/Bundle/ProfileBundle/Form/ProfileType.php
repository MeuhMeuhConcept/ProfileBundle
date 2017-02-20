<?php

namespace MMC\Profile\Bundle\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('uuid', TextType::class, ['label' => 'UUID']);
        // $builder->add('roles', TextType::class, ['label' => 'roles']);
        $builder->add('type', TextType::class, ['label' => 'type']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('translation_domain', 'profile');
    }

    public function getName()
    {
        return 'profile';
    }
}
