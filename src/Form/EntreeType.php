<?php

namespace App\Form;

use App\Entity\Entree;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntreeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_fournisseur',null,['required'=>true])
            ->add('date',null,['widget'=>'single_text'])
            ->add('qte_a',null,['required'=>true])
            ->add('prix_achat',null,['required'=>true])
            ->add('prix_vente',null,['required'=>true])
            ->add('article',null,['placeholder'=>'Libellé d\'article','required'=>true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entree::class,
        ]);
    }
}
