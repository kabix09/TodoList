<?php
require_once '../init.php';

use App\Connection\Connection;
use App\Logger\Logger;
use App\Logger\MessageSheme;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Session\Session;
use App\Session\SessionManager;
use App\Token\Token;

$logger = new Logger();
$session = new Session();
$sessionManager = new SessionManager($session);

if(!$sessionManager->manage())
{
    $config = new MessageSheme($_SERVER['REMOTE_ADDR'], __CLASS__, __FUNCTION__);
    $logger->critical("The user requesting access to the session could not be verified", [$config]);

}else if(!isset($_GET['key']) || empty($_GET['key']) || !isset($_GET['email']) || empty($_GET['email']))
{
    $config = new MessageSheme($_SERVER['REMOTE_ADDR'], __CLASS__, __FUNCTION__);
    $logger->error("missing parameter: 'email' or 'key' get variable", [$config]);

}else{
    $key = urlencode($_GET['key']);
    $email = urlencode($_GET['email']);

    try{
        // now we are sure, we catch varification parameters
        // try find user by key
        $userRepository = new UserRepository(new Connection(include DB_CONFIG));
        $user = $userRepository->fetchByEmail($email);

        if(is_null($user))
            throw new \Exception("account with that email doesn't exists");

        if($user->getKey() !== $key)
            throw new \Exception('incorrect activation key');

        $new = new UserManager($user, $userRepository);

        if($new->activateTheAccount())
        {
            // remove old key
            $new->changeAccountKey();

            // log event
            $config = new MessageSheme($user->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $logger->info("Successfully activated account", [$config]);

            // save user in session
            $session['user'] = $user;
        }
        else
            throw new RuntimeException("The account with id: {$session['user']->getId()} couldn't be activated");
    }catch (\Exception $e)
    {
        $config = new MessageSheme($session['user']->getNick() ?? $_SERVER['REMOTE_ADDR'], __CLASS__, __FUNCTION__, TRUE);
        $logger->error($e->getMessage(), [$config]);

        header("Location: ../index.php");
    }
}

header("Location: ../index.php");
