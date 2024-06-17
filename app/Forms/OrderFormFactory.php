<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;

class OrderFormFactory
{    
    public function render(): Form
    {
        $form = new Form();
        $form->addText('fname', 'Jméno:')
            ->setRequired('Prosím, vyplňte jméno.');
        $form->addText('lname', 'Příjmení:')
            ->setRequired('Prosím, vyplňte příjmení.');
        $form->addText('street', 'Ulice, č.p.:')
            ->setRequired('Prosím, vyplňte ulici a č.p.');
        $form->addText('city', 'Město:')
            ->setRequired('Prosím, vyplňte město.');
        $form->addText('phone', 'Telefon:')
            ->setRequired('Prosím, vyplňte telefon.');
        $form->addText('email', 'E-mail:')
            ->setRequired('Prosím, vyplňte e-mail.')
            ->setDisabled();
        $form->addText('timeInput', 'Hodina:')
        ->setRequired('Prosím, vyplňte hodinu.')
        ->setAttribute('type', 'time');      
        $form->addText('dateInput', 'Datum:')
        ->setRequired('Prosím, vyplňte datum.')
        ->setAttribute('type', 'date');
        $form->addTextArea('infoText', 'Poznámka:');
        $form->addSubmit('send', 'Pokračovat');
        $form->onValidate[] = [$this, 'validateOrderForm'];
        return $form;
    }

    public function validateOrderForm(Form $form, \stdClass $data): void
    {
        $userInputDate = $data->dateInput;
        $userInputTime = $data->timeInput;

        $combinedDateTimeString = $userInputDate . ' ' . $userInputTime;

        $minTime = \DateTime::createFromFormat('H:i', '08:00');
        $maxTime = \DateTime::createFromFormat('H:i', '20:00');
        $minDate = (new \DateTime())->add(new \DateInterval('P2D'));

        $userDateTime = \DateTime::createFromFormat('Y-m-d H:i', $combinedDateTimeString);
        if ($userDateTime->getTimestamp() < $minDate->getTimestamp()) {
            $currentTime = new \DateTime();
            $form['dateInput']->addError('Nejbližší volný termín - ' . $minDate->format('j.n.') .' '. $currentTime->format('H:i'));
        }

        if ($userDateTime->format('H:i') < $minTime->format('H:i') || $userDateTime->format('H:i') > $maxTime->format('H:i')) {
            $form['timeInput']->addError('Sloužíme Vám v čase 8:00 - 20:00');
        }
    }
}