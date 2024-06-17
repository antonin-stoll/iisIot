<?php

namespace App\Form;

use App\Entity\KPI;
use App\Entity\Parameter;
use App\Repository\ParameterRepository;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KPIFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $device = $options['device'];
        $builder
            ->add('name', null, [
                'label' => 'name*',
            ])
            ->add('expression', null, [
                'label' => 'expression*',
            ])
            ->add('parameter', EntityType::class, [
                'class' => Parameter::class,
                'choice_label' => 'name',
                'label' => 'Parameter*',
                'placeholder' => 'Choose a Parameter',
                'query_builder' => function (ParameterRepository $sr) use ($device) {
                    return $sr->createQueryBuilder('p')
                        ->where('p.device = :device')
                        ->setParameter('device', $device);
                },
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => KPI::class,
            'device' => null,
        ]);
    }
}
