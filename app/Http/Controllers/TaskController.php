<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = $this->taskService->getUserTasks();
        return response()->json($tasks);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data=   $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'=>'nullable',
            'completed_at'=>'nullable',
            'is_completed'=>'nullable'
        ]);
        $data['user_id']=auth()->user()->id;
        $task=$this->taskService->createTask($data);
        return response()->json($task, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $task = $this->taskService->getTask($id);
        return response()->json($task, 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = $this->taskService->updateTask($id, $data);

        return response()->json($task, 200);
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $this->taskService->deleteTask($id);
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

}
