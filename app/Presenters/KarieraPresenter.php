<?php



declare(strict_types=1);



namespace App\Presenters;



use Nette;

use Nette\Application\UI\Form;

use App\Models\CareerFacade;

use DateTime;



final class KarieraPresenter extends Nette\Application\UI\Presenter

{

    public function __construct(private CareerFacade $careerFacade)

    {

    }

    

    public function actionDefault()

    {

        $this->template->style = "kariera";

    }

    

    protected function createComponentCareerForm(): Form

    {

        $form = new Form();

        $form->addText('name','Jméno')  

            ->setRequired('Zadejte své jméno');

        $form->addText('surname','Příjmení')  

            ->setRequired('Zadejte své příjmení');

        $form->addSelect('gender', 'Pohlaví', ['M' => 'Muž', 'F' => 'Žena']);

        $form->addEmail('email','Email')

            ->addRule(Form::EMAIL, 'Zadejte platný e-mail')

            ->setRequired('Zadejte svůj email');

        $form->addDate('dayB', 'Datum narození')

            ->setRequired('Zadejte své datum narození')

            ->setFormat('Y-m-d');

        $form->addText('phone', 'Telefon')

            ->setRequired('Uveďte své telefonní číslo')

            ->setAttribute('pattern', '^\d{9}$')

            ->addRule(Form::PATTERN, 'Telefonní číslo musí mít 9 číslic, žádné mezery ani předčíslí', '^\d{9}$');        

        $form->addSelect('education', 'Dosažené vzdělání', ['1' => 'Základní vzdělání', '2' => 'Střední vzdělání', '3' => 'Vyšší odborné vzdělání', '4' => 'Vysokoškolské vzdělání']);

        $form->addTextArea('position')

            ->setRequired('Zadejte na  kterou pozici se hlásíte.');

        $form->addTextArea('hobbies')

            ->setRequired('Zadejte jaké jsou vaše záliby');

        $form->addTextArea('why')

            ->setRequired('Zadejte proč zrovna my?');

        $form->addTextArea('skills')

            ->setRequired('Zadejte své dovednosti');
        $form->addSubmit('send', 'Zkusit štěstí');

        $form->onValidate[] = [$this, 'validateCareerForm'];

        $form->onSuccess[] = [$this, 'careerFormSuccess'];

        return $form;

    }

    

    public function validateCareerForm(Form $form, \stdClass $data): void

    {

        $minAgeDate = (new \DateTime())->sub(new \DateInterval('P15Y'));

        $maxAgeDate = (new \DateTime())->sub(new \DateInterval('P120Y'));



        $userInput = $data->dayB;

        $userBirthDate = \DateTime::createFromFormat('Y-m-d', $userInput);



        if ($userBirthDate->getTimestamp() > $minAgeDate->getTimestamp()) {

            $form['dayB']->addError('Minimální věk - 15 let');

        }elseif ($userBirthDate->getTimestamp() < $maxAgeDate->getTimestamp()) {

            $form['dayB']->addError('Maximální stáří - 120 let');

        }

    }



    public function careerFormSuccess(Form $form)

    {

        $values = $form->getValues();



        $userBirthDate = \DateTime::createFromFormat('Y-m-d', $values->dayB);

        $calculatedAge = (new \DateTime())->diff($userBirthDate)->y;



        date_default_timezone_set('Europe/Prague');

        $date = date('Y-m-d H:i:s');



        $careerData = [

            'date_arrive' => $date,

            'name' => $values->name,

            'surname' => $values->surname,

            'age' => $calculatedAge,

            'gender' => $values->gender,

            'phone' => $values->phone,

            'email' => $values->email,

            'education' => $values->education,

            'position' => $values->position,

            'hobbies' => $values->hobbies,

            'why' => $values->why,

            'skills' => $values->skills,

        ];  



        try {

			$this->careerFacade->addCareer($careerData);

            $this->flashMessage('Vše se podařilo', 'success');

            $this->redirect('Home:');

        } catch (Nette\Database\UniqueConstraintViolationException $e) {

			throw new DuplicateEmailException;

		}

    }

}

