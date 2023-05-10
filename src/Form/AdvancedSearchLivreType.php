<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AdvancedSearchLivreType extends AbstractType
{
    const notes = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
                'required' => false,
                'empty_data' => '',
            ])
            ->add('noteMin', ChoiceType::class, [
                'required' => false,
                'empty_data' => '0',
                'label' => 'Note minimum',
                'choices' => array_combine(self::notes, self::notes)
            ])
            ->add('noteMax', ChoiceType::class, [
                'required' => false,
                'empty_data' => '20',
                'label' => 'Note maximum',
                'choices' => array_combine(self::notes, self::notes)
            ])
            ->add('dateMin',DateType::class, [
                'widget' => 'single_text', 
                'format' => 'yyyy-MM-dd',
                'required' => false,
            ])
            ->add('dateMax',DateType::class, [
                'widget' => 'single_text', 
                'format' => 'yyyy-MM-dd',
                'required' => false,
            ])
            ->add('recherche', SubmitType::class)
        ;
    }

}
