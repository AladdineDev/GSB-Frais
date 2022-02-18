<?php

namespace App\Form;

use App\Entity\Lignefraishorsforfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class LignefraishorsforfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'years' => range(date('Y') - 1, date('Y'))
            ])
            ->add('montant', NumberType::class, [
                'attr' => [
                    'placeholder' => 0,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lignefraishorsforfait::class,
        ]);
    }
}
