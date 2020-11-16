<?php

namespace App\Presenters;

use App\Infrastructure\Repositories\UserRepository;
use App\Presenters\BasePresenter;
use App\Repositories\ProjectDetailRepository;
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
    public function __construct(UserRepository $userRepository, PasswordEncrypter $passwordEncrypter)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncrypter = $passwordEncrypter;
    }

    /**
     * Renders default view (default.latte).
     */
    public function renderDefault() {

    }


    /**
     * @return BootstrapForm
     */
    protected function createComponentProfileEditForm(): BootstrapForm
    {
        $form = new BootstrapForm;
        $form->renderMode = RenderMode::VERTICAL_MODE;
        $row = $form->addRow();
        $row->addCell(6)
            ->addPassword('password', 'New password')
            ->setRequired('Please enter your new password');


        $secondRow= $form->addRow();
        $secondRow->addCell(6)
            ->addPassword('passwordVerification', 'Password verification')
            ->setRequired('Please enter your verification password')
            ->addRule($form::EQUAL, 'Entered password are not correct.', $form['password']);


        $form->addSubmit('send', 'Change password');
        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }

    /**
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

        $this->flashMessage('Password has been successfully changed.');
        $this->redirect('Homepage:');
    }
}