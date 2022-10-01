<?php

namespace App\Form;

use App\Entity\Videojoc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideojocType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titol')
            ->add('cantitat')
            ->add('dataCreacio')
            ->add('preu')
            ->add('portada')
            ->add('resena')
            ->add('generes')
            ->add('plataformas')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Videojoc::class,
            'csrf_protection'=>false
        ]);
    }
}
