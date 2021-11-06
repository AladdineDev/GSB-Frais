<?php

namespace App\Form;

use App\Entity\Fichefrais;
use App\Form\LignefraisforfaitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FichefraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lignefraisforfaits', CollectionType::class, [
                'entry_type' => LignefraisforfaitType::class,
                'entry_options' => ['label' => 'false'],
            ])
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fichefrais::class,
        ]);
    }
}
