<?php

namespace App\Entity;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert ;

class ChangePassword
{
    #[SecurityAssert\UserPassword(message: 'Votre mot de passe actuel est incorrect !')]
    protected $oldPassword;
    #[Assert\Length(min:"4",minMessage: "Votre mot de passe doit contenir au min 4 caractères !")]
    protected $password;
    #[Assert\EqualTo(propertyPath: "password",message:"Veuillez entrer le même mot de passe !")]
    protected $confirm ;


    function getOldPassword() {
        return $this->oldPassword;
    }

    function getPassword() {
        return $this->password;
    }
    function getConfirm() {
        return $this->confirm;
    }


    function setOldPassword($oldPassword) {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    function setConfirm($confirm) {
        $this->confirm = $confirm;
        return $this;
    }

}
