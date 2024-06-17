<?php

namespace App\Form;

use App\Entity\Device;
use App\Entity\System;
use App\Repository\SystemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceAssignFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('system', EntityType::class, [
                'mapped' => false,
                'class' => System::class,
                'choice_label' => 'name',
                'label' => 'Select System',
                'placeholder' => 'Choose a system',
                'query_builder' => function (SystemRepository $sr) use ($user) {
                    return $sr->createQueryBuilder('s')
                        ->where('s.Owner = :owner')
                        ->setParameter('owner', $user);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Device::class,
            'user' => null,
        ]);
    }
}
