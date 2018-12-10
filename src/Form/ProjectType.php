<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label'=>'Nom du projet'])
          //  ->add('slug', null, ['label'=>'Slug'])
            ->add('programmed_At', DateTimeType::class, [
                'widget'=> 'single_text',
                'html5' => true,
                'label'=>'Date de programmation'] )
            ->add('is_Published', null, ['label'=>'Publié à l\'externe (O/N)'])
          // ->add('published_at', null, ['label'=>'Date de publication'])
            ->add('image', null, ['label'=>'Nom du fichier de l\'image'])
            ->add('description', null, ['label'=>'Description (éventuellement en html)'])
            ->add('url',null,[ 'label'=>'url de référence'])
            ->add('langages', null, [
                "multiple"=>true,
                "expanded"=>true
            ])
            // ->add('envoyer',SubmitType::class)// Pour ajouter un bouton
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
