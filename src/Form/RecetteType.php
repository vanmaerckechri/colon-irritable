<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idAuth')
            ->add('titre')
            ->add('nbrPers', null, [
                'label' => 'Nombre de Personnes'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de Plat',
                'choices' => $this->getChoices(Recette::RECETTE_TYPE)
            ])
            ->add('tempsPrepa', TimeType::class, [
                'label' => 'Temps de Préparation'
            ])/*
            ->add('image', FileType::class, [
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }

    private function getChoices($choices)
    {
        $output = [];
        foreach ($choices as $key => $value){
            $output[$value] = $key;
        }
        return $output;
    }
}
