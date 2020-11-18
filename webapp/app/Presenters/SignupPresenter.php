<?php

namespace App\Presenters;

use App\Infrastructure\Repositories\UserRepository;
use Nette;
use Contributte\FormsBootstrap\BootstrapForm;
use Contributte\FormsBootstrap\Enums\RenderMode;


/**
 * Class SignupPresenter
 * @package App\Presenters
 */
final class SignupPresenter extends BasePresenter {
    private $database;
    private $userRepository;

    /**
     * SignupPresenter constructor.
     * @param Nette\Database\Context $database
     * @param UserRepository $userRepository
     */
    public function __construct(Nette\Database\Context $database, UserRepository $userRepository) {
        $this->database = $database;
        $this->userRepository = $userRepository;
    }

    /**
     * Creation of registration form.
     * @return BootstrapForm Bootstrap form instance.
     */
    protected function createComponentRegistrationForm(): BootstrapForm
    {
        $form = new BootstrapForm;
        $form->renderMode = RenderMode::VERTICAL_MODE;
        $row = $form->addRow();
        $row->addCell(6)
            ->addText('username', 'Uživatelské jméno')
            ->setRequired('Prosím zadejte vaše uživatelské jméno');


        $secondRow= $form->addRow();
        $secondRow->addCell(6)
            ->addPassword('password', 'Heslo')
            ->setRequired('Prosím zadejte vaše heslo');


        $form->addSubmit('send', 'Zaregistrovat se');
        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }

    /**
     * Signup form success event handler.
     * @param BootstrapForm $form
     * @param $data
     * @throws Nette\Application\AbortException
     */
    public function formSucceeded(BootstrapForm $form, $data): void
    {
        // tady zpracujeme data odeslaná formulářem
        // $data->name obsahuje jméno
        // $data->password obsahuje heslo

        $this->userRepository->signUpUser($data->username, $data->password);


        $this->flashMessage('Byl jste úspěšně registrován.');
        $this->redirect('Homepage:');
    }

    public function renderDefault() {

    }

}
