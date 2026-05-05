<?php

namespace App\Form;

use App\Entity\Controler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tempsPasse', TextType::class, [
                'label' => 'Temps passé (ex: 30min)',
                'attr' => ['placeholder' => 'Durée de l\'intervention sur ce matériel'],
                'required' => false,
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire technique',
                'attr' => ['rows' => 3, 'placeholder' => 'Observations, pièces changées...'],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Controler::class,
        ]);
    }
}
