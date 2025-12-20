<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Country;
use App\Models\Tag;
use Mockery;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;
    protected $product;
    protected $country;
    protected $tags;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role_id' => 2]); 
        $this->admin = User::factory()->create(['role_id' => 1]); 
        
        $this->country = Country::create([
            'name' => 'Test Country',
            'code' => 'TC',
            'reduction'=>''
        ]);
        
        $this->tags = [
            Tag::create(['name' => 'Tag 1', 'slug' => 'tag-1']),
            Tag::create(['name' => 'Tag 2', 'slug' => 'tag-2']),
            Tag::create(['name' => 'Tag 3', 'slug' => 'tag-3']),
        ];
        

        $this->product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'preview' => 'test.jpg',
            'country_id' => $this->country->id,
            'user_id' => $this->user->id,
            
        ]);
        
        $this->product->tags()->attach(collect($this->tags)->pluck('id')->toArray());
    }

    public function test_requires_authentication()
    {
        $response = $this->get(route('admin.products'));
        $response->assertRedirect(route('login'));
    }


    public function test_returns_view_with_products_for_authenticated_user()
    {
        $this->actingAs($this->admin);
        
        $mockService = Mockery::mock(ProductService::class);
        $mockService->shouldReceive('getAllProducts')
            ->once()
            ->andReturn(collect([$this->product]));
        
        $this->app->instance(ProductService::class, $mockService);
        
        $response = $this->get(route('admin.products'));
        
        $response->assertOk();
        $response->assertViewIs('pages.admin.products.index');
        $response->assertViewHas('allProducts');
    }


    public function test_requires_authentication_for_creating_user()
    {
        $response = $this->get(route('admin.products.create'));
        $response->assertRedirect(route('login'));
    }


    public function test_create_method_returns_view_with_countries_and_tags()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get(route('admin.products.create'));
        
        $response->assertOk();
        $response->assertViewIs('pages.admin.products.create');
        $response->assertViewHas('countries');
        $response->assertViewHas('tags');
        
        $viewData = $response->original->getData();
        $this->assertNotEmpty($viewData['countries']);
        $this->assertNotEmpty($viewData['tags']);
    }

    public function test_store_method_requires_authentication()
    {
        $response = $this->post(route('admin.product.store'), []);
        $response->assertRedirect(route('login'));
    }

    public function test_store_method_validates_required_fields()
    {
        $this->actingAs($this->admin);
        
        $response = $this->post(route('admin.product.store'), []);
        
        $response->assertSessionHasErrors(['name', 'country_id']);
        $response->assertSessionDoesntHaveErrors(['description', 'preview', 'tags']);
    }


    public function test_store_method_validates_name_min_length()
    {
        $this->actingAs($this->admin);
        
        $response = $this->post(route('admin.product.store'), [
            'name' => 'ab',
            'country_id' => $this->country->id,
        ]);
        
        $response->assertSessionHasErrors(['name']);
    }


    public function twst_store_method_validates_country_exists()
    {
        $this->actingAs($this->admin);
        
        $response = $this->post(route('admin.product.store'), [
            'name' => 'Test Product',
            'country_id' => 999,
        ]);
        
        $response->assertSessionHasErrors(['country_id']);
    }

    public function store_method_validates_tags_exist()
    {
        $this->actingAs($this->admin);
        
        $response = $this->post(route('admin.product.store'), [
            'name' => 'Test Product',
            'country_id' => $this->country->id,
            'tags' => [999, 1000],
        ]);
        
        $response->assertSessionHasErrors(['tags.0', 'tags.1']);
    }

    public function test_store_method_creates_product_with_tags()
    {
        $this->actingAs($this->admin);
        
        $mockService = Mockery::mock(ProductService::class);
        $newProduct = Product::make([
            'id' => 999,
            'name' => 'New Product',
            'user_id' => $this->admin->id,
            'country_id' => $this->country->id,
        ]);
        
        $mockService->shouldReceive('createProduct')
            ->with(Mockery::on(function ($data) {
                return $data['name'] === 'New Product' && 
                       $data['country_id'] == $this->country->id &&
                       $data['user_id'] == $this->admin->id;
            }))
            ->once()
            ->andReturn($newProduct);
        
        $this->app->instance(ProductService::class, $mockService);
        
        $response = $this->post(route('admin.product.store'), [
            'name' => 'New Product',
            'description' => 'Product description',
            'preview' => 'preview.jpg',
            'country_id' => $this->country->id,
            'tags' => [1, 2],
        ]);
        
        $response->assertRedirect(route('admin.products'));
        $response->assertSessionHas('success', 'Товар создан успешно');
    }


    public function test_store_method_handles_service_exception(): void
    {
        $this->actingAs($this->admin);
        
        $mockService = Mockery::mock(ProductService::class);
        $mockService->shouldReceive('createProduct')
            ->once()
            ->andThrow(new \Exception('Service error message'));
        
        $this->app->instance(ProductService::class, $mockService);
        

        $response = $this->post(route('admin.product.store'), [
            'name' => 'New Product',
            'country_id' => $this->country->id,
        ]);
        
        $response->assertStatus(500);
    }

    public function test_edit_method_requires_authentication()
    {
        $response = $this->get(route('admin.product.edit', $this->product));
        $response->assertRedirect(route('login'));
    }


    public function test_edit_method_authorizes_admin_access()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get(route('admin.product.edit', $this->product));
        
        $response->assertOk();
        $response->assertViewIs('pages.admin.products.edit');
        $response->assertViewHas('product');
        $response->assertViewHas('tags');
        $response->assertViewHas('countries');
    }


    public function edit_method_authorizes_author_access()
    {
        $this->actingAs($this->user); 
        
        $response = $this->get(route('admin.product.edit', $this->product));
        
        $response->assertOk();
    }


    public function test_edit_method_denies_access_for_non_admin_non_author()
    {
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role_id' => 2,
        ]);
        
        $this->actingAs($otherUser);
        
        $response = $this->get(route('admin.product.edit', $this->product));
        
        $response->assertForbidden();
    }


    public function test_update_method_requires_authentication()
    {
        $response = $this->put(route('admin.product.update', $this->product), []);
        $response->assertRedirect(route('login'));
    }

    public function test_update_method_validates_required_fields()
    {
        $this->actingAs($this->admin);
        
        $response = $this->put(route('admin.product.update', $this->product), []);
        
        $response->assertSessionHasErrors(['name', 'country_id']);
    }

    public function test_update_method_handles_service_exception()
    {
        $this->actingAs($this->admin);
        
        $mockService = Mockery::mock(ProductService::class);
        $mockService->shouldReceive('updateProduct')
            ->once()
            ->andThrow(new \Exception('Service error'));
        
        $this->app->instance(ProductService::class, $mockService);
        
        $response = $this->put(route('admin.product.update', $this->product), [
            'name' => 'Updated Product',
            'country_id' => $this->country->id,
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_destroy_method_requires_authentication()
    {
        $response = $this->delete(route('admin.products.delete', $this->product->id));
        $response->assertRedirect(route('login'));
    }


    public function test_destroy_method_deletes_product_successfully()
    {
        $this->actingAs($this->admin);
        
        $mockService = Mockery::mock(ProductService::class);
        $mockService->shouldReceive('deleteProductById')
            ->with($this->product->id)
            ->once()
            ->andReturn(true);
        
        $this->app->instance(ProductService::class, $mockService);
        
        $response = $this->delete(route('admin.products.delete', $this->product->id));
        
        $response->assertRedirect(route('admin.products'));
        $response->assertSessionHas('success', 'Товар успешно удалён');
    }


    public function test_destroy_method_handles_service_exception()
    {
        $this->actingAs($this->admin);
        
        $mockService = Mockery::mock(ProductService::class);
        $mockService->shouldReceive('deleteProductById')
            ->with($this->product->id)
            ->once()
            ->andThrow(new \Exception('Delete error'));
        
        $this->app->instance(ProductService::class, $mockService);
        
        $response = $this->delete(route('admin.products.delete', $this->product->id));
        
        $response->assertRedirect();

    }

    public function test_show_method_is_not_implemented()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get("/admin/products/{$this->product->id}");
        
        $response->assertStatus(404);
    }


    public function test_store_method_sets_authenticated_user_id()
    {
        $this->actingAs($this->user);
        
        $mockService = Mockery::mock(ProductService::class);
        $newProduct = Product::make([
            'id' => 888,
            'user_id' => $this->user->id,
        ]);
        
        $mockService->shouldReceive('createProduct')
            ->with(Mockery::on(function ($data) {
                return $data['user_id'] === $this->user->id;
            }))
            ->once()
            ->andReturn($newProduct);
        
        $this->app->instance(ProductService::class, $mockService);
        
        $this->post(route('admin.product.store'), [
            'name' => 'User Product',
            'country_id' => $this->country->id,
        ]);
        
        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}