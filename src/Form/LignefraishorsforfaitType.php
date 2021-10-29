<?php

namespace App\Form;

use App\Entity\Lignefraishorsforfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LignefraishorsforfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('idvisiteur')
            // ->add('mois')
            ->add('libelle')
            ->add('date')
            ->add('montant')
            ->add('ajouter', SubmitType::class)
            // ->add('idfichefrais');
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lignefraishorsforfait::class,
        ]);
    }
}
