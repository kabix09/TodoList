<?php
namespace App\Manager;

use App\Entity\Factory\TaskFactory;
use App\Entity\Task;
use App\Repository\TaskRepository;

final class TaskManager
{
    private Task $task;
    private TaskRepository $repository;

    public function __construct($data, TaskRepository $taskRepository)
    {
        if(is_array($data))
            $this->task = TaskFactory::arrayToEntity($data);
        elseif($data instanceof Task)
            $this->task = $data;
        else
            $this->task = (new TaskFactory())->newTask();

        $this->repository = $taskRepository;
    }

    public function return():Task{
        return $this->task;
    }


    public function setCreateDate(?Task $task = NULL){
        ($task ?? $this->task)->setCreateDate(
            $this->getDate()
        );
    }

    public function setAuthor(string $author, bool $flag = FALSE, ?Task $task = NULL) : void {
        ($task ?? $this->task)->setAuthor($author);

        if($flag)
            ($task ?? $this->task)->setOwner($author);

        /* return
            $this->repository->update($this, [
                "where" => ["id", "= '{$task->getId()}'"]
            ]);

        */
    }

    public function setOwner(string $newOwner, ?Task $task = NULL) : void{
        ($task ?? $this->task)->setOwner($newOwner);
    }

    /*
     * status:
     * 1) start data > current data || start data == null -> planned
     * 2) start data <= current data && (finsh data <= current data || finish data == null) -> started
     * 3) finish data < current data -> finished
     */
    public function setStatus(?Task $task = NULL) : void {
        if(is_null(($task ?? $this->task)->getStartDate()))
        {
            ($task ?? $this->task)->setStatus("prepared");
        }else
        {
            if(($task ?? $this->task)->getStartDate() > (new \DateTime())->format("Y-m-d"))
            {
                ($task ?? $this->task)->setStatus("planned");
            }else
            {
                if(is_null(($task ?? $this->task)->getTargetEndDate()) ||  ($task ?? $this->task)->getTargetEndDate() <= (new \DateTime())->format("Y-m-d"))
                {
                    ($task ?? $this->task)->setStatus("started");
                }else{
                    ($task ?? $this->task)->setStatus("finished");
                }
            }
        }
    }

    public function changeOwner(Task $task, string $newOwner) : bool{
        $task->setOwner($newOwner);

        return
            $this->repository->update($this, [
                "where" => ["id", "= '{$task->getId()}'"]
            ]);
    }

    public function changeStatus(Task $task, string $newStatus) : bool {
        if(!$this->validStatus($newStatus))
            return FALSE;

        $task->setStatus($newStatus);

        return
            $this->repository->update($this, [
                "where" => ["id", "= '{$task->getId()}'"]
            ]);
    }

    private function validStatus(string $newStatus) : bool{
        if(!in_array($newStatus, Task::STATUS))
            throw new \RuntimeException("illegal status");

        return TRUE;
    }

    private function getDate($date = NULL) : string {
        return
            (new \DateTime())->format('Y-m-d');
    }


    public function toArray(): array {
        return TaskFactory::entityToArray($this->task);
    }

}