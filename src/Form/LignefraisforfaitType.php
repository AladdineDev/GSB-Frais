<?php

namespace App\Form;

use App\Entity\Fraisforfait;
use App\Entity\Lignefraisforfait;
use App\Repository\FraisforfaitRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LignefraisforfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite')
            // ->add('ficheFrais')
            // ->add('fraisForfait')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lignefraisforfait::class,
        ]);
    }
}
