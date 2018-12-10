<?php

namespace App\Form;

use App\Entity\Langages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LangagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lg_nom',null, ['label'=>'Nom du langage'])
           // ->add('slug')
            ->add('isFront',null, ['label'=>'est un front office?'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Langages::class,
        ]);
    }
}
