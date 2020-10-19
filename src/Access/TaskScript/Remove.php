<?php
namespace App\Access\TaskScript;

use App\Access\BaseTaskScript;
use App\Logger\MessageSheme;

final class Remove extends BaseTaskScript
{
    protected function main(array $queryParams): void
    {
        if($this->taskRepository->remove([
                "WHERE" =>NULL,
                "AND" => ["id = '{$queryParams[self::QUERY_VARIABLES[self::ID]]}'", "owner = '{$queryParams[self::QUERY_VARIABLES[self::OWNER]]}'"]
            ]))
        {
            // log event
            $config = new MessageSheme($this->session['user']->getNick(), __CLASS__, __FUNCTION__, TRUE);
            $this->logger->info("Successfully removed task with id: {$queryParams[self::QUERY_VARIABLES[self::ID]]}", [$config]);
            // no need to remove from session because
            // index.php automatically refresh task list
            $this->redirectToHome();
        }else{
            throw new \RuntimeException("An attempt to remove task with id: {$queryParams[self::QUERY_VARIABLES[self::ID]]} has failed");
        }
    }
}