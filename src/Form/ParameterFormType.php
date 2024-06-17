<?php

namespace App\Form;

use App\Entity\Parameter;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $broker = $options['broker'];
        if($broker)
        {
            $builder
                ->add('value');
        }
        else
        {
            $builder
                ->add('name', null, [
                    'label' => 'name*',
                ])
                ->add('minValue')
                ->add('FmaxValue');
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parameter::class,
            'broker' => false,
        ]);
    }
}
