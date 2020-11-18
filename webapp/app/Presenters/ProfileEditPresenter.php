<?php

namespace App\Presenters;

use App\Infrastructure\Repositories\UserRepository;
use App\Presenters\BasePresenter;
use App\Repositories\ProjectDetailRepository;
use App\Services\Authenticator;
use App\Services\PasswordEncrypter;
use Contributte\FormsBootstrap\BootstrapForm;
use Contributte\FormsBootstrap\Enums\RenderMode;

/**
 * Class ProfileEditPresenter
 * @package App\Presenters
 */
final class ProfileEditPresenter extends BasePresenter {

    private $passwordEncrypter;
    private $userRepository;

    /** @var Authenticator @inject */
    public $authentificatorService;

    /**
     * ProfileEditPresenter constructor.
     * @param UserRepository $userRepository
     * @param PasswordEncrypter $passwordEncrypter
     */
    public function __construct(UserRepository $userRepository, PasswordEncrypter $passwordEncrypter)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncrypter = $passwordEncrypter;
    }

    /**
     * Renders default view (default.latte).
     */
    public function renderDefault() {
        if(!($this->user->isLoggedIn())) {
            $this->redirect('Homepage:default');
        }
    }


    /**
     * Creation of profile edit form.
     * @return BootstrapForm Instance of Boostrap form.
     */
    protected function createComponentProfileEditForm(): BootstrapForm
    {
        $form = new BootstrapForm;
        $form->renderMode = RenderMode::VERTICAL_MODE;
        $row = $form->addRow();
        $row->addCell(6)
            ->addPassword('password', 'Nové heslo')
            ->setRequired('Prosím zadejte nové heslo.');


        $secondRow= $form->addRow();
        $secondRow->addCell(6)
            ->addPassword('passwordVerification', 'Potvrzení hesla')
            ->setRequired('Prosím zadejte potvrzení vašeho hesla.')
            ->addRule($form::EQUAL, 'Zadané hesla se neshodují.', $form['password']);


        $form->addSubmit('send', 'Změnit heslo');
        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }

    /**
     * Profile form success event handler.
     * @param BootstrapForm $form
     * @param $data
     * @throws \Nette\Application\AbortException
     */
    public function formSucceeded(BootstrapForm $form, $data): void
    {
        // tady zpracujeme data odeslaná formulářem
        // $data->name obsahuje jméno
        // $data->password obsahuje heslo

        $encryptedInputPassword = $this->passwordEncrypter->encryptPassword($data->password);
        $this->userRepository->changeUserPassword($this->user->getId(), $encryptedInputPassword);

        $this->user->logout();

        $this->flashMessage('Vaše heslo bylo úspěšně změněno.');
        $this->redirect('Homepage:');
    }
}