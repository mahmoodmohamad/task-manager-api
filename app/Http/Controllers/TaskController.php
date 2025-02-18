<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    use AuthorizesRequests;
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
        return response()->json(TaskResource::collection($tasks));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        //
        $data=$request->validated();
        $data['user_id']=auth()->user()->id;
        $task=$this->taskService->createTask($data);
        return response()->json(new TaskResource($task), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $task = $this->taskService->getTask($id);

        $this->authorize('view', $task); // ✅ استخدم السياسة هنا

        return response()->json($task, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, $id): JsonResponse
    {
        $data = $request->validated();

        // Retrieve the task before updating
        $task = $this->taskService->getTask($id);

        // Check authorization
        $this->authorize('update', $task);

        // Perform update
        $task = $this->taskService->updateTask($id, $data);

        return response()->json(new TaskResource($task), 200);
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
