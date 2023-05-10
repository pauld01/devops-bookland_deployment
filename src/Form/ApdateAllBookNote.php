<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ApdateAllBookNote extends AbstractType
{
    const notes = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('val', ChoiceType::class, [
                'label' => 'Augmenter tous les livres de cet auteur de :',
                'choices' => array_combine(self::notes, self::notes)
            
            ])
            ->add('Augmenter', SubmitType::class)
        ;

    }

}