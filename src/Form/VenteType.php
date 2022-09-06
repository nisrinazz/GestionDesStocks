<?php

namespace App\Form;

use App\Entity\Vente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class,[
                'widget' => 'single_text'
            ])
            ->add('qte_c')
            ->add('mode_paiement',ChoiceType::class,['choices'=>Vente::Methods,
                'multiple'=>false,'placeholder'=>'Méthode de paiement'])
            ->add('article',null,[
                'required'=>true,
                'placeholder'=>'Libellé d\'article'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vente::class,
        ]);
    }
}
