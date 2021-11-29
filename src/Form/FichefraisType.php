<?php

namespace App\Form;

use App\Entity\Fichefrais;
use App\Form\LignefraisforfaitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FichefraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ligneFraisForfaits', CollectionType::class, [
                'entry_type' => LignefraisforfaitType::class,
                'entry_options' => ['label' => 'false'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fichefrais::class,
        ]);
    }
}
