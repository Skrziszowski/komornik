<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use App\Models\UserFacade;
use Nette\Security\Passwords;

class UserFormFactory
{
    public function __construct(private UserFacade $userFacade, private User $user, private Passwords $passwords)
    {
    }

    public function render(): Form
    {
        $form = new Form();
        $form->addText('name','Jméno')
            ->setRequired();
        $form->addText('surname','Příjmení')
            ->setRequired();
        $form->addDate('dayB', 'Datum narození')
            ->setRequired();
        $form->addEmail('mail','Email')
            ->setDisabled();
        $form->addSelect('gender', 'Pohlaví', ['M' => 'Muž', 'F' => 'Žena'])
            ->setRequired();
        $form->addText('phone', 'Telefon')
            ->setRequired('Uveďte své telefonní číslo')
            ->setAttribute('pattern', '^\d{9}$')
            ->addRule(Form::PATTERN, 'Telefonní číslo musí mít 9 číslic, žádné mezery ani předčíslí', '^\d{9}$');        
        $form->addText('address', 'Adresa');
        $form->addText('city', 'Město');  
        $form->addSubmit('send', 'Přepsat data');
        $form->onValidate[] = [FormValidator::class, 'validateBirthDate'];
        return $form;
    }

    public function saveButtonPressed(Form $form)
    {
        $values = $form->getValues();
        $day = $values->dayB->format('Y-m-d');
        $userData = [
            'name' => $values->name,
            'surname' => $values->surname,
            'gender' => $values->gender,
            'phone' => $values->phone,
            'dayB' => $day,
            'address' => $values->address,
            'city' => $values->city
        ];
        try{
            $this->userFacade->updateUserData((int)$values->id, $userData);
            $form->getPresenter()->flashMessage('Vaše data byla změněna', 'success');
            $form->getPresenter()->redirect('this');
        } catch (\Exception $e){
            $form->getPresenter()->flashMessage('Chyba při změně dat', 'danger');
            $form->getPresenter()->redirect('this'); 
        }
    }

    public function deleteButtonPressed(Form $form){
        $values = $form->getValues();
        try{
            $this->userFacade->deleteUser((int)$values->id);
            $form->getPresenter()->redirect('dashboard');
            $form->getPresenter()->flashMessage('Uživatel odstraněn', 'success');
        } catch (\Exception $e){
            $form->addError('Chyba při změně dat', 'danger');
            $form->getPresenter()->redirect('this'); 
        }
    }

    public function profileFormSuccess(Form $form)
    {
        $values = $form->getValues();
        $day = $values->dayB->format('Y-m-d');
        $id = $this->user->getIdentity()->getId();
        $userData = [
            'name' => $values->name,
            'surname' => $values->surname,
            'gender' => $values->gender,
            'phone' => $values->phone,
            'dayB' => $day,
            'address' => $values->address,
            'city' => $values->city
        ];
        try{
            $this->userFacade->updateUserData($id, $userData);
        } catch (\Exception $e){
            $form->getPresenter()->flashMessage('Chyba při změně dat', 'danger');
            $form->getPresenter()->redirect('this'); 
        }
        $form->getPresenter()->redirect('this'); 
    }

    public function renderPasswordForm()
    {
        $form = new Form();
        $form->addPassword('old','Zadejte staré heslo')
            ->setRequired();
        $form->addPassword('new','Zadejte nové heslo')
            ->setRequired();
        $form->addPassword('new2','Zadejte nové heslo znovu')
            ->setRequired();     
        $form->addSubmit('send', 'Změnit heslo');
        $form->onSuccess[] = [$this, 'PasswordFormSuccess'];
        return $form;
    }
    
    public function PasswordFormSuccess(Form $form)
    {
        $values = $form->getValues();
        $userFormId = $this->user->getIdentity()->getId();
        if ($values->new !== $values->new2){
            $form->getPresenter()->flashMessage('Nová hesla nejsou shodné', 'danger');
            $form->getPresenter()->redirect('this');
        }

        if (!$this->passwords->verify($values->old,$this->userFacade->getPasswordHashByUserId($userFormId)->password)){
            $form->getPresenter()->flashMessage('Vaše staré heslo je chybné', 'danger');
            $form->getPresenter()->redirect('this');
        }

        if ($this->passwords->verify($values->new,$this->userFacade->getPasswordHashByUserId($userFormId)->password)){
            $form->getPresenter()->flashMessage('Vaše nové heslo je to staré.', 'danger');
            $form->getPresenter()->redirect('this');
        }
        try {
            $this->userFacade->changePassword($userFormId, $values->new);
            $form->getPresenter()->flashMessage('Heslo bylo změněno', 'success');
        } catch (\Exception $e) {
            $form->getPresenter()->flashMessage('Chyba při změně', 'danger');
            $form->getPresenter()->redirect('this'); 
		}

        $form->getPresenter()->redirect('this'); 
    }
}