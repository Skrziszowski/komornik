<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use App\Utils\Authenticator;

class RegisterFormFactory
{    
    public function __construct(private Authenticator $authenticator)
    {
    }

    public function render(): Form
    {
        $form = new Form();
        $form->getErrors();
        $form->addText('email', 'Email')
            ->addRule(Form::EMAIL, 'Zadejte platný e-mail')
            ->setRequired('Zvolte email pro přihlášení');
        $form->addPassword('password', 'Heslo')
            ->setRequired('Zvolte heslo');
        $form->addPassword('password_again', 'Heslo znovu')
            ->setRequired('Zadejte heslo znovu pro ověření')
            ->addConditionOn($form['password'], $form::Equal, $form['password'])
            ->addRule($form::Equal, "Hesla se neshodují", $form['password']);
        $form->addText('name', 'Jméno')
            ->setRequired('Zadejte své jméno');
        $form->addText('surname', 'Příjmení')
            ->setRequired('Zadejte své příjmení');
        $form->addSelect('gender', 'Pohlaví', ['M' => 'Muž', 'F' => 'Žena'])
            ->setRequired();
        $form->addDate('dayB', 'Datum:')
            ->setRequired('Zadejte datum narození');
        $form->addText('phone', 'Telefon')
        ->setRequired('Uveďte své telefonní číslo')
        ->setAttribute('pattern', '^\d{9}$')
        ->addRule(Form::PATTERN, 'Telefonní číslo musí mít 9 číslic, žádné mezery ani předčíslí', '^\d{9}$');        
        $form->addSubmit('send', 'Zaregistrovat se');
        $form->onValidate[] = [FormValidator::class, 'validateBirthDate'];
        $form->onSuccess[] = [$this, 'registerFormSuccess'];
        return $form;
    }

    public function registerFormSuccess(Form $form)
    {
        $values = $form->getValues();
        $day = $values->dayB->format('Y-m-d');
        $userData = [
            'name' => $values->name,
            'surname' => $values->surname,
            'gender' => $values->gender,
            'email' => $values->email,
            'password' => $values->password,
            'phone' => $values->phone,
            'dayB' => $day
        ];

        try {
            $this->authenticator->add($userData);
            $form->getPresenter()->flashMessage('Registrace úspěšná - nyní se můžete přihlásit', 'success');
        } catch (\App\Utils\DuplicateEmailException $e) {
            $form->addError('Uživatelský účet již existuje');
        } catch (\Exception $e) {
            $form->addError('Chyba při registraci','danger');
        } finally {
            if(!$form->hasErrors()) {
                $form->getPresenter()->redirect('Admin:login');
            }
        }
    }
}