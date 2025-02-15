<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_get_only_his_tasks(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Task::factory()->create(['user_id' => $user1->id, 'title' => 'User1 Task']);
        Task::factory()->create(['user_id' => $user2->id, 'title' => 'User2 Task']);

        $this->actingAs($user1);
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'User1 Task'])
            ->assertJsonMissing(['title' => 'User2 Task']);
    }

    /** @test */
    public function user_can_insert_task(): void
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'Title Task',
            'description' => 'This is a test task',
            'due_date' => '2024-12-11',
            'completed_at' => '2025-02-12',
            'is_completed' => true,
        ];

        $this->actingAs($user);
        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Title Task',
            'description' => 'This is a test task',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_update_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'title' => 'User Task']);

        $data = [
            'title' => 'Updated Task Title',
            'description' => 'Updated description',
            'due_date' => '2024-12-11',
            'completed_at' => '2025-02-12',
            'is_completed' => true,
        ];

        $this->actingAs($user);
        $response = $this->putJson("/api/tasks/{$task->id}", $data);

        $response->assertStatus(200);
        $response->assertJson([ 'id' => $task->id, 'title' => 'Updated Task Title', 'user_id' => $user->id ]);

        $this->assertDatabaseHas('tasks', [ 'id' => $task->id, 'title' => 'Updated Task Title', 'description' => 'Updated description' ]);
    }

    /** @test */
    public function user_can_show_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'title' => 'User Task']);

        $this->actingAs($user);
        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $response->assertJson(['id' => $task->id, 'title' => 'User Task', 'user_id' => $user->id]);
    }
    public function test_user_can_delete_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'title' => 'User Task']);

        $this->actingAs($user);
        $response = $this->deleteJson("/api/tasks/{$task->id}");

        // التأكد من أن الاستجابة صحيحة
        $response->assertStatus(200);

        // التأكد أن المهمة لم تعد موجودة في قاعدة البيانات
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
