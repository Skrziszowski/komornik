<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;

class LoginFormFactory
{    
    public function __construct(private User $user)
    {
    }

    public function render(): Form
    {
        $form = new Form();
        $form->addText('email', 'Email')->setRequired('Zadejte e-mail');
        $form->addPassword('password', 'Heslo')->setRequired('Zadejte heslo');
        $form->addSubmit('send', 'Přihlásit se');
        $form->onSuccess[] = [$this, 'logInFormSuccess'];
        return $form;
    }

    public function logInFormSuccess(Form $form, \stdClass $values): void
    {
        try {
            $this->user->login($values->email,$values->password);
            $form->getPresenter()->redirect('Home:default');
        } catch (\Nette\Security\AuthenticationException $e) {
            if ($e->getCode() === \Nette\Security\IAuthenticator::IDENTITY_NOT_FOUND) {
                $form->addError('Zadané přihlašovací údaje nejsou správné');
            } else {
                $form->addError($e->getMessage());
            }
        }
    }
}