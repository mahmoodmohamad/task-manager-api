<?php
namespace App\Repositories;

use App\Models\Task;
class TaskRepository
{
    public function getTasksByUser($userId)
    {
        return Task::where('user_id', $userId)->get();
    }
    public function showTask($id, $userId)
    {
        return Task::where('id', $id)->where('user_id', $userId)->firstOrFail();
    }
    public function create(array $data)
    {
        return Task::create($data);
    }
    public function update($id, $userId, array $data)
    {
        $task = Task::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $task->update($data);
        return $task;
    }

    public function delete($id, $userId)
    {
        $task = Task::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $task->delete();
    }

}
