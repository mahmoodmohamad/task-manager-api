<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService
{
    protected $taskRepository;
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository=$taskRepository;
    }
    public function getUserTasks()
    {
        return $this->taskRepository->getTasksByUser(auth()->id());
    }
public function getTask($id)
{
    return $this->taskRepository->showTask($id, auth()->id());
}
    public function createTask(array $data)
    {
    return $this->taskRepository->create($data);
    }
    public function updateTask($id, array $data)
    {
        return $this->taskRepository->update($id, auth()->id(), $data);
    }

    public function deleteTask($id)
    {
        $this->taskRepository->delete($id, auth()->id());
    }

}
