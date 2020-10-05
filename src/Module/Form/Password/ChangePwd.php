<?php
namespace App\Module\Form\Password;

use App\Connection\Connection;
use App\Entity\User;
use App\Manager\UserManager;
use App\Module\Form\PasswordForm;

final class ChangePwd extends PasswordForm
{
    public function __construct(array $formData, Connection $connection, User $user)
    {
        parent::__construct($formData, $connection);
        $this->object = $user;
    }

    protected function doHandler()
    {
        if (!$this->checkPassword())
        {
            $this->errors['repeatPassword'][] = self::PASSWORD_ERROR;
            $this->processStatus = self::PROCESS_STATUS[0];
        }

        if($this->processStatus == NULL)
        {
            $this->processStatus = self::PROCESS_STATUS[2];

            $this->notify();

            if ($this->processStatus === self::PROCESS_STATUS[2])
            {
                $userManager = new UserManager(NULL, $this->repository);

                // insert new user
                if(!$userManager->changePassword($this->object, $this->data['password']))
                {
                    throw new \RuntimeException("password couldn't be changed");
                }else
                {
                    $this->logger->info("Successfully changed password", [
                        "personalLog" => TRUE,
                        "userFingerprint" => $this->object->getNick(),
                        "className" => __CLASS__,
                        "functionName" => __FUNCTION__
                    ]);
                }

                // change status
                $this->processStatus = self::PROCESS_STATUS[1];
            }
        }else{
            $this->logger->error("An attempt to change password on \"{$this->object->getNick()}\" account has failed", [
                "personalLog" => TRUE,
                "userFingerprint" => $this->object->getNick(),
                "className" => __CLASS__,
                "functionName" => __FUNCTION__
            ]);
        }
    }

}