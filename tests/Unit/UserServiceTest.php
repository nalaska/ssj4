<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = $this->createMock(UserService::class);
    }

    public function testCreateUser()
    {
        $request = new Request([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'belt' => 'bleu',
            'phone' => '1234567890',
            'year_of_registration' => 2021,
            'roles' => ['admin'],
            'date_of_birth' => '1990-01-01',
            'attendance' => true,
        ]);

        // Vérifier l'effet de la méthode create
        $user = $this->userService->createUser($request);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);

        // Vérifier que l'utilisateur a été créé dans la base de données
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function testUpdateUser()
    {
        $user = User::factory()->create();
        $request = new Request([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'belt' => 'verte',
            'roles' => ['user'],
        ]);

        $updatedUser = $this->userService->updateUser($request, $user);

        $this->assertEquals('Jane Doe', $updatedUser->name);
    }

    public function testDeleteUser()
    {
        $user = User::factory()->create();

        $result = $this->userService->deleteUser($user);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function testGetUsersByBeltOrder()
    {
        User::factory()->create(['belt' => 'jaune']);
        User::factory()->create(['belt' => 'bleu']);
        User::factory()->create(['belt' => 'blanche']);

        $users = $this->userService->getUsersByBeltOrder();

        $this->assertCount(3, $users);
    }
}