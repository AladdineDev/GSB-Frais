<?php

namespace App\Form;

use App\Entity\Visiteur;
use App\Repository\VisiteurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VisiteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', EntityType::class, [
                'class' => Visiteur::class,
                'placeholder' => '-- Veuillez sélectionner un visiteur --',
                'query_builder' => function (VisiteurRepository $visiteurRepository) {
                    return $visiteurRepository->createQueryBuilder('v')
                        ->addOrderBy('v.nom', 'ASC')
                        ->addOrderBy('v.prenom', 'ASC');
                }
            ])
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visiteur::class,
        ]);
    }
}
