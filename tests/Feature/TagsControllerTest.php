<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tag;
use Mockery;
use App\Services\TagsServece;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $tag;
    protected $mockService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role_id' => 1]);
        
        $this->user = User::factory()->create(['role_id' => 2]); 
        
        $this->tag = Tag::create([
            'name' => 'Test Tag',
            'description' => 'Test Description',
        ]);
        
        $this->mockService = Mockery::mock(TagsServece::class);
        $this->app->instance(TagsServece::class, $this->mockService);
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.tags'));
        $response->assertRedirect(route('login'));
    }
    
    public function test_index_returns_view_with_tags(): void
    {
        $this->actingAs($this->admin);

        $collection = new Collection([$this->tag]);

        $this->mockService->shouldReceive('getAllTags')
            ->once()
            ->andReturn($collection);
        
        $response = $this->get(route('admin.tags'));
        
        $response->assertOk();
        $response->assertViewIs('pages.admin.tags.index');
        $response->assertViewHas('tags');
        
        $viewData = $response->original->getData();
        $this->assertNotEmpty($viewData['tags']);
    }
    
    public function test_index_accessible_by_non_admin_users(): void
    {
        $this->actingAs($this->user); 
        $collection = new Collection([$this->tag]);
        $this->mockService->shouldReceive('getAllTags')
            ->once()
            ->andReturn($collection);
        
        $response = $this->get(route('admin.tags'));
        
        $response->assertOk();
    }

    
    public function test_create_requires_authentication(): void
    {
        $response = $this->get(route('admin.tags.create'));
        $response->assertRedirect(route('login'));
    }
    
    public function test_create_requires_admin_role(): void
    {
        $this->actingAs($this->user); 
        
        $response = $this->get(route('admin.tags.create'));
        
        $response->assertForbidden(); 
    }
    
    public function test_create_returns_view_for_admin(): void
    {
        $this->actingAs($this->admin);
        
        $response = $this->get(route('admin.tags.create'));
        
        $response->assertOk();
        $response->assertViewIs('pages.admin.tags.create');
    }

    
    public function test_edit_requires_authentication(): void
    {
        $response = $this->get(route('admin.tag.edit', $this->tag->id));
        $response->assertRedirect(route('login'));
    }
    
    public function test_edit_requires_admin_role(): void
    {
        $this->actingAs($this->admin);
        
        $this->mockService->shouldReceive('getTagById')
            ->with($this->tag->id)
            ->once()
            ->andReturn($this->tag);
    
        $response = $this->get(route('admin.tag.edit', $this->tag->id));
        
        $response->assertSuccessful();
    }
    public function test_edit_forbidden_for_user_role(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.tag.edit', $this->tag->id));
        
        $response->assertForbidden();
    }
    
    public function test_edit_returns_view_with_tag_for_admin(): void
    {
        $this->actingAs($this->admin);
        
        $this->mockService->shouldReceive('getTagById')
            ->with($this->tag->id)
            ->once()
            ->andReturn($this->tag);
        
        $response = $this->get(route('admin.tag.edit', $this->tag->id));
        
        $response->assertOk();
        $response->assertViewIs('pages.admin.tags.edit');
        $response->assertViewHas('tag');
        
        $viewData = $response->original->getData();
        $this->assertEquals($this->tag->id, $viewData['tag']->id);
    }

    public function test_store_requires_authentication(): void
    {
        $response = $this->post(route('admin.tag.store'), []);
        $response->assertRedirect(route('login'));
    }
    
    public function test_store_requires_admin_role(): void
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('admin.tag.store'), [
            'name' => 'New Tag',
        ]);
        
        $response->assertForbidden();
    }
    
    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin);
        
        $response = $this->post(route('admin.tag.store'), []);
        
        $response->assertSessionHasErrors(['name']);
        $response->assertSessionDoesntHaveErrors(['description']);
    }
    
    public function test_store_validates_name_max_length(): void
    {
        $this->actingAs($this->admin);
        
        $response = $this->post(route('admin.tag.store'), [
            'name' => str_repeat('a', 256), 
        ]);
        
        $response->assertSessionHasErrors(['name']);
    }
    
    public function test_store_validates_description_max_length(): void
    {
        $this->actingAs($this->admin);
        
        $response = $this->post(route('admin.tag.store'), [
            'name' => 'Valid Name',
            'description' => str_repeat('a', 501), // 501 символов
        ]);
        
        $response->assertSessionHasErrors(['description']);
    }
    
    public function test_store_creates_tag_successfully(): void
    {
        $this->actingAs($this->admin);
        
        $newTagData = [
            'name' => 'New Tag Name',
            'description' => 'New Tag Description',
        ];
        
        $newTag = new Tag($newTagData);
        $newTag->id = 999;
        
        $this->mockService->shouldReceive('createTag')
            ->with($newTagData)
            ->once()
            ->andReturn($newTag);
        
        $response = $this->post(route('admin.tag.store'), $newTagData);
        
        $response->assertRedirect(route('admin.tags'));
        $response->assertSessionHas('success', 'Тег "' . $newTagData['name'] . '" успешно создан!');
    }
    
    public function test_store_handles_duplicate_name_exception(): void
    {
        $this->actingAs($this->admin);
        
        $tagData = [
            'name' => 'Duplicate Tag',
            'description' => 'Description',
        ];
        
        $this->mockService->shouldReceive('createTag')
            ->with($tagData)
            ->once()
            ->andThrow(new \Exception('Тег с таким названием уже существует'));
        
        $response = $this->post(route('admin.tag.store'), $tagData);
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Тег с таким названием уже существует');
        $response->assertSessionHasInput('name', 'Duplicate Tag');
    }
    
    public function test_store_handles_general_exception(): void
    {
        $this->actingAs($this->admin);
        
        $tagData = [
            'name' => 'New Tag',
            'description' => 'Description',
        ];
        
        $this->mockService->shouldReceive('createTag')
            ->with($tagData)
            ->once()
            ->andThrow(new \Exception('Some database error'));
        
        $response = $this->post(route('admin.tag.store'), $tagData);
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Some database error');
    }


    
    public function test_delete_requires_authentication(): void
    {
        $response = $this->delete(route('admin.tag.delete', $this->tag->id));
        $response->assertRedirect(route('login'));
    }
    
    public function test_delete_requires_admin_role(): void
    {
        $this->actingAs($this->user);
        
        $response = $this->delete(route('admin.tag.delete', $this->tag->id));
        
        $response->assertForbidden();
    }
    
    public function test_delete_deletes_tag_successfully(): void
    {
        $this->actingAs($this->admin);
        

        $this->mockService->shouldReceive('deleteTagById')
            ->with($this->tag->id)
            ->once()
            ->andReturn(true);
        
        $response = $this->delete(route('admin.tag.delete', $this->tag->id));
        
        $response->assertRedirect(route('admin.tags'));

    }
    
    public function test_delete_handles_exception(): void
    {
        $this->actingAs($this->admin);
        
        $this->mockService->shouldReceive('deleteTagById')
            ->with($this->tag->id)
            ->once()
            ->andThrow(new \Exception('Delete failed'));
        
        $response = $this->delete(route('admin.tag.delete', $this->tag->id));
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Delete failed');
    }

    
    public function test_update_requires_authentication(): void
    {
        $response = $this->put(route('admin.tag.update', $this->tag->id), []);
        $response->assertRedirect(route('login'));
    }
    
    public function test_update_requires_admin_role(): void
    {
        $this->actingAs($this->user);
        
        $response = $this->put(route('admin.tag.update', $this->tag->id), [
            'name' => 'Updated Name',
        ]);
        
        $response->assertForbidden();
    }
    
    public function test_update_validates_required_fields(): void
    {
        $this->actingAs($this->admin);
        
        $response = $this->put(route('admin.tag.update', $this->tag->id), []);
        
        $response->assertSessionHasErrors(['name']);
    }
    
    public function test_update_updates_tag_successfully(): void
    {
        $this->actingAs($this->admin);
        
        $updateData = [
            'name' => 'Updated Tag Name',
            'description' => 'Updated Description',
        ];
        
        $updatedTag = new Tag($updateData);
        $updatedTag->id = $this->tag->id;
        

        $this->mockService->shouldReceive('update')
            ->with($this->tag->id, $updateData)
            ->once()
            ->andReturn($updatedTag);
        
        $response = $this->put(route('admin.tag.update', $this->tag->id), $updateData);
        
        $response->assertRedirect(route('admin.tags'));
    }
    
    public function test_update_handles_duplicate_name_exception(): void
    {
        $this->actingAs($this->admin);
        
        $updateData = [
            'name' => 'Duplicate Name',
            'description' => 'Description',
        ];
        
        $this->mockService->shouldReceive('update')
            ->with($this->tag->id, $updateData)
            ->once()
            ->andThrow(new \Exception('Тег с таким названием уже существует'));
        
        $response = $this->put(route('admin.tag.update', $this->tag->id), $updateData);
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Тег с таким названием уже существует');
    }
    
    public function test_update_handles_general_exception(): void
    {
        $this->actingAs($this->admin);
        
        $updateData = [
            'name' => 'Updated Name',
            'description' => 'Description',
        ];
        
        $this->mockService->shouldReceive('update')
            ->with($this->tag->id, $updateData)
            ->once()
            ->andThrow(new \Exception('Database error'));
        
        $response = $this->put(route('admin.tag.update', $this->tag->id), $updateData);
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Database error');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}