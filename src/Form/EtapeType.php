<?php

namespace App\Form;

use App\Entity\Etape;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtapeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Etape::class,
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
