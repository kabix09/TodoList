<?php
namespace App\Module\Login;

use App\Connection\Connection;
use App\Entity\User;
use App\Filter\Filter;
use App\Module\Observer\Observable;
use App\Module\Observer\Observer;
use App\Repository\UserRepository;
use App\Token\Token;

final class Login implements Observable
{
    const INCORRECT_LOGIN = "incorrect login";
    const INCORRECT_PASSWORD = "incorrect password";
    const PROCESS_STATUS = ["errors", "correct" , "session"];

    private array $observers = [];

    private array $data;
    private $user;
    private array $errors = [];

    private $processStatus = NULL;
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    public function __construct(array $formData, array $dbConfig)
    {
        $this->data = $formData;
        $this->repository = new UserRepository(new Connection($dbConfig));
    }

    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer)
    {
        $this->observers = array_filter(
            $this->observers,
            function($object) use ($observer){
                return (!($object === $observer));
            }
        );
    }

    public function notify()
    {
        foreach ($this->observers as $observer)
        {
            $observer->update($this);
        }
    }

    // ######################################################################
    public function loginHandler(?string $serverToken = NULL, array $filter, array $assignments): bool{

        try {

            if ($this->checkToken($serverToken)) {

                if (!$this->validData($filter, $assignments))
                {
                    $this->processStatus = self::PROCESS_STATUS[0];
                } elseif (!$this->checkNick())
                {
                    $this->errors['nick'][] = self::INCORRECT_LOGIN;
                    $this->processStatus = self::PROCESS_STATUS[0];
                } elseif (!$this->checkPassword())
                {
                    $this->errors['password'][] = self::INCORRECT_PASSWORD;
                    $this->processStatus = self::PROCESS_STATUS[0];
                }

                if ($this->processStatus === NULL)
                {
                    $this->processStatus = self::PROCESS_STATUS[2];

                    $this->notify();

                    $this->processStatus = self::PROCESS_STATUS[1];
                }


                $this->notify();

                if (empty($this->errors))
                    return TRUE;
            }
        }catch(\Exception $e)
        {
            var_dump($e->getMessage());
            die();
        }

        return FALSE;
    }

    // ----------------------------------------------------------------------
    public function checkToken(?string $serverToken = NULL): bool{
        if(!isset($serverToken))
            throw new \RuntimeException("token doesn't exists on server side ://");

        if(sodium_compare(
                (new Token($serverToken))->hash()->getToken(),
                (new Token($this->data['hidden']))->decode()->getToken()
            ) !== 0
        ) throw new \RuntimeException('detected cross-site attack on login form');

        unset($this->data['hidden']);
        //unset($serverToken);    // when arg is passing by reference -> doesn't work --- WHY ???

        return TRUE;
    }

    public function validData(array $filter, array $assignments): bool{
        $filter = new Filter($filter, $assignments);
        $filter->process($this->data);

        foreach ($filter->getMessages() as $key => $value)
        {
            $this->errors[$key] = $value;
        }

        if(!empty($this->errors))
        {
            return FALSE;
        }

        return TRUE;
    }

    public function checkNick() : bool {
        $this->user =  $this->repository->fetchByNick($this->data['nick']);

        if(! $this->user )
        {
            return FALSE;
        }

        return TRUE;
    }

    public function checkPassword():bool {
        if(!password_verify(
                $this->data['password'],
                $this->user->getPassword()
            )){
            return FALSE;
        }
        return TRUE;
    }

    // =======================================================================
    public function getProcessStatus():string{
        return $this->processStatus;
    }
    public function getErrors(): array{
        return $this->errors;
    }
    public function getUser(): User{
        return $this->user;
    }
}