<?php

namespace App\Presenters;

use App\Infrastructure\Repositories\UserRepository;
use App\Services\Authenticator;
use App\Services\PasswordEncrypter;
use Contributte\FormsBootstrap\BootstrapForm;
use Contributte\FormsBootstrap\Enums\RenderMode;
use Nette;

/**
 * User auth presenter for handling login process.
 * @package App\Presenters
 */
final class UserAuthPresenter extends BasePresenter {
    private $authenticator;
    private $database;
    private $passwordEncrypter;
    private $userRepository;

    /**
     * UserAuthPresenter constructor.
     * @param Nette\Database\Context $database
     * @param UserRepository $userRepository
     * @param Authenticator $authenticator
     * @param PasswordEncrypter $passwordEncrypter
     */
    public function __construct(Nette\Database\Context $database, UserRepository $userRepository,
                                Authenticator $authenticator, PasswordEncrypter  $passwordEncrypter) {
        $this->authenticator = $authenticator;
        $this->database = $database;
        $this->passwordEncrypter = $passwordEncrypter;
        $this->userRepository = $userRepository;
    }

    /**
     * Creation of login form.
     * @return BootstrapForm Returns Boostrap form.
     */
    protected function createComponentLoginForm(): BootstrapForm
    {
        $form = new BootstrapForm;
        $form->renderMode = RenderMode::VERTICAL_MODE;
        $row = $form->addRow();
        $row->addCell(6)
            ->addText('username', 'Enter username...')
            ->setRequired('Please enter your username');


        $secondRow= $form->addRow();
        $secondRow->addCell(6)
            ->addPassword('password', 'Enter password...')
            ->setRequired('Please enter your password');


        $form->addSubmit('send', 'Login in');
        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }

    /**
     * Form processing handler.
     * @param BootstrapForm $form Bootstrap form instance.
     * @param $data data Passed form data.
     * @throws Nette\Application\AbortException
     * @throws Nette\Security\AuthenticationException
     */
    public function formSucceeded(BootstrapForm $form, $data): void
    {
        // tady zpracujeme data odeslaná formulářem
        // $data->name obsahuje jméno
        // $data->password obsahuje heslo
        try {
            $this->authenticator->authenticate([$data->username, $data->password]);
            $this->user->login($data->username, $data->password);
        } catch (Nette\Security\AuthenticationException $e) {
            $this->flashMessage('The username or password you entered is incorrect.');
            return;
        }

        //$this->flashMessage('Byl jste úspěšně přihlášen.');
        $this->redirect('Homepage:');
    }

    /**
     * Renders default view (default.latte).
     */
    public function renderDefault() {

    }


}
