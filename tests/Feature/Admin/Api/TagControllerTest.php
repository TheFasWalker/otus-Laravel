<?php

namespace Tests\Feature\Admin\Api;

use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;
    protected User $regularUser;
    protected string $adminToken;
    protected string $userToken;
    protected array $tagStructure;
    protected array $successResponseStructure;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем роли
        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Administrator']);
        $userRole = Role::create(['name' => 'User', 'description' => 'Regular User']);
        $adminRole = Role::updateOrCreate(
        ['id' => 1],
        ['name' => 'Admin', 'description' => 'Administrator']
        );
        // Создаем админа с api_token (role_id = 1 как в Gate)
        $this->adminToken = Str::random(60);
        $this->adminUser = User::factory()->create([
            'role_id' => $adminRole->id,
            'api_token' => $this->adminToken
        ]);

        // Создаем обычного пользователя с api_token
        $this->userToken = Str::random(60);
        $this->regularUser = User::factory()->create([
            'role_id' => $userRole->id,
            'api_token' => $this->userToken
        ]);

        // Структура тега для проверки
        $this->tagStructure = [
            'id',
            'name',
            'description'
        ];

        // Структура успешного ответа для создания/обновления
        $this->successResponseStructure = [
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'description'
            ],
            'created_at',
            'id'
        ];
    }

    /**
     * Тест просмотра списка тегов (доступен всем авторизованным)
     */
    public function test_index_returns_all_tags_for_authorized_user(): void
    {
        // Создаем несколько тегов через модель
        Tag::create(['name' => 'Test Tag 1', 'description' => 'Description 1']);
        Tag::create(['name' => 'Test Tag 2', 'description' => 'Description 2']);
        Tag::create(['name' => 'Test Tag 3', 'description' => null]);

        // Админ получает список с токеном в заголовке
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->getJson('/api/tags');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->tagStructure
                ]
            ])
            ->assertJsonCount(5, 'data'); // Проверяем что в data 5 элементов (2 из миграции + 3 созданных)

        // Обычный пользователь тоже получает список
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->getJson('/api/tags');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }


    /**
     * Тест просмотра списка тегов без токена
     */
    public function test_index_returns_unauthorized_without_token(): void
    {
        $response = $this->getJson('/api/tags');
        $response->assertStatus(401);
    }

    /**
     * Тест просмотра списка тегов с неверным токеном
     */
    public function test_index_returns_unauthorized_with_invalid_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token_123'
        ])->getJson('/api/tags');

        $response->assertStatus(401);
    }

    // /**
    //  * Тест просмотра конкретного тега
    //  */
    public function test_show_returns_specific_tag(): void
    {
        $tag = Tag::create([
            'name' => 'Unique Tag',
            'description' => 'Unique Description'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->getJson("/api/tags/{$tag->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->tagStructure
            ])
            ->assertJson([
                'data' => [
                    'id' => $tag->id,
                    'name' => 'Unique Tag',
                    'description' => 'Unique Description'
                ]
            ]);
    }

    /**
     * Тест просмотра несуществующего тега
     */
    public function test_show_returns_404_for_nonexistent_tag(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->getJson('/api/tags/99999');

        $response->assertStatus(404);
    }

    // /**
    //  * Тест успешного создания тега администратором
    //  */
    public function test_store_creates_tag_successfully_by_admin(): void
    {
        $tagData = [
            'name' => 'New Test Tag',
            'description' => 'New Test Description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->postJson('/api/tags', $tagData);

        $response->assertStatus(201)
            ->assertJsonStructure($this->successResponseStructure)
            ->assertJson([
                'success' => true,
                'message' => 'Тег "New Test Tag" успешно создан!',
                'data' => [
                    'name' => 'New Test Tag',
                    'description' => 'New Test Description'
                ]
            ]);

        $this->assertDatabaseHas('tags', [
            'name' => 'New Test Tag',
            'description' => 'New Test Description'
        ]);
    }

    /**
     * Тест создания тега с дублирующимся именем
     */
    public function test_store_fails_with_duplicate_name(): void
    {
        $tagData = [
            'name' => 'tag1', 
            'description' => 'New Description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->postJson('/api/tags', $tagData);

        $response->assertStatus(422) 
            ->assertJsonValidationErrors(['name'])
            ->assertJson([
                'message' => 'The name has already been taken.',
                'errors' => [
                    'name' => ['The name has already been taken.']
                ]
            ]);
    }

    /**
     * Тест создания тега обычным пользователем (доступ запрещен)
     */
    public function test_store_forbidden_for_regular_user(): void
    {
        $tagData = [
            'name' => 'Test Tag',
            'description' => 'Test Description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->postJson('/api/tags', $tagData);

        $response->assertStatus(403);
    }

    /**
     * Тест валидации при создании тега
     */
    public function test_store_validation_fails(): void
    {
        // Пустой запрос
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->postJson('/api/tags', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        // Имя слишком длинное
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->postJson('/api/tags', [
            'name' => str_repeat('a', 256),
            'description' => 'Description'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Тест успешного обновления тега администратором
     */
    public function test_update_modifies_tag_successfully_by_admin(): void
    {
        $tag = Tag::create([
            'name' => 'Original Name',
            'description' => 'Original Description'
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'description' => 'Updated Description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->putJson("/api/tags/{$tag->id}", $updateData);

        $response->assertStatus(201)
            ->assertJsonStructure($this->successResponseStructure)
            ->assertJson([
                'success' => true,
                'message' => 'Тег "Updated Name" успешно обновлен!',
                'data' => [
                    'name' => 'Updated Name',
                    'description' => 'Updated Description'
                ]
            ]);

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => 'Updated Name',
            'description' => 'Updated Description'
        ]);
    }

    /**
     * Тест обновления тега обычным пользователем (доступ запрещен)
     */
    public function test_update_forbidden_for_regular_user(): void
    {
        $tag = Tag::create([
            'name' => 'Test Tag',
            'description' => 'Test Description'
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'description' => 'Updated Description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->putJson("/api/tags/{$tag->id}", $updateData);

        $response->assertStatus(403);
    }

    /**
     * Тест успешного удаления тега администратором
     */
    public function test_destroy_deletes_tag_successfully_by_admin(): void
    {
        $tag = Tag::create([
            'name' => 'Tag to Delete',
            'description' => 'Will be deleted'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->deleteJson("/api/tags/{$tag->id}");

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Тег успешно Удалён!'
            ]);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    /**
     * Тест удаления тега обычным пользователем (доступ запрещен)
     */
    public function test_destroy_forbidden_for_regular_user(): void
    {
        $tag = Tag::create([
            'name' => 'Test Tag',
            'description' => 'Test Description'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->deleteJson("/api/tags/{$tag->id}");

        $response->assertStatus(403);
    }

}