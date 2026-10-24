<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function staff_cannot_access_user_management()
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->get('/users');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_user_management()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);
    }

    /** @test */
    public function staff_cannot_create_products()
    {
        $staff = User::factory()->staff()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($staff)->post('/products', [
            'sku'            => 'TST-001',
            'name'           => 'Test Product',
            'category_id'    => $category->id,
            'purchase_price' => 10000,
            'selling_price'  => 15000,
            'minimum_stock'  => 5,
            'unit'           => 'pcs',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_products()
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post('/products', [
            'sku'            => 'TST-001',
            'name'           => 'Test Product',
            'category_id'    => $category->id,
            'purchase_price' => 10000,
            'selling_price'  => 15000,
            'minimum_stock'  => 5,
            'unit'           => 'pcs',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['sku' => 'TST-001']);
    }

    /** @test */
    public function staff_can_view_products()
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->get('/products');
        $response->assertStatus(200);
    }

    /** @test */
    public function staff_cannot_delete_products()
    {
        $staff = User::factory()->staff()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($staff)->delete("/products/{$product->id}");
        $response->assertStatus(403);
    }

    /** @test */
    public function staff_can_record_stock_movements()
    {
        $staff = User::factory()->staff()->create();
        $product = Product::factory()->withStock(10)->create();

        $response = $this->actingAs($staff)->post('/stock-movements/in', [
            'product_id' => $product->id,
            'quantity'   => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type'       => 'in',
            'quantity'   => 5,
        ]);
    }
}
