<?php

namespace AppBundle\Form;

use MMC\Profile\Component\Provider\ProfileTypeProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileTypeTest extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', ChoiceType::class, [
            'choices' => ['TYPE1' => 'TYPE1', 'TYPE2' => 'TYPE2', 'TYPE3' => 'TYPE3'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('translation_domain', 'profile');
    }

    public function getName()
    {
        return 'profileTest';
    }
}
