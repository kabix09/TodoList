<?php
namespace App\Service\Session;

use App\Entity\Session;

class SessionVerify
{
    const DEFAULT_SESSION_DURATION_TIME = 120;   // two minute

    private Session $session;
    private int $sessionDurationTime;

    public function __construct(Session $session, ?int $sessionDurationTime = NULL)
    {
        $this->session = $session;
        $this->sessionDurationTime = $sessionDurationTime ?? self::DEFAULT_SESSION_DURATION_TIME;
    }

    public function checkUserAgent(): bool{
        return
            ($this->session->getBrowserData() === $_SERVER['HTTP_USER_AGENT']);
    }

    public function checkUserIP(): bool{
        return
            ($this->session->getUserIP() === $_SERVER['REMOTE_ADDR']);
    }

    public function isLoginSessionExpired(): bool{
        if(isset($_SESSION['user']) &&
            (time() - \DateTime::createFromFormat(Session::DATE_FORMAT, $this->session->getCreateTime())->getTimestamp()) > $this->sessionDurationTime) {
            return TRUE;
        }

        return FALSE;
    }
}