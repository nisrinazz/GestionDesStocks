<?php
namespace App\Form;

use App\Entity\ChangePassword;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ResetPasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('oldPassword', PasswordType::class)
            ->add('password',PasswordType::class)
            ->add('confirm',PasswordType::class)
        ;
    }


    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => ChangePassword::class,
            'csrf_token_id' => 'change_password',
        ));
    }

}
