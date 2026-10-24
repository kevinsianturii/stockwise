<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->actingAs($this->admin);
    }

    /** @test */
    public function product_list_is_accessible()
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_a_valid_product()
    {
        $category = Category::factory()->create();

        $response = $this->post('/products', [
            'sku'            => 'TST-001',
            'name'           => 'Produk Uji Coba',
            'category_id'    => $category->id,
            'purchase_price' => 10000,
            'selling_price'  => 15000,
            'minimum_stock'  => 5,
            'unit'           => 'pcs',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', [
            'sku'           => 'TST-001',
            'name'          => 'Produk Uji Coba',
            'current_stock' => 0, // Must start at 0
        ]);
    }

    /** @test */
    public function product_requires_sku()
    {
        $category = Category::factory()->create();

        $response = $this->post('/products', [
            'name'           => 'Produk Tanpa SKU',
            'category_id'    => $category->id,
            'purchase_price' => 10000,
            'selling_price'  => 15000,
            'minimum_stock'  => 5,
            'unit'           => 'pcs',
        ]);

        $response->assertSessionHasErrors('sku');
    }

    /** @test */
    public function product_sku_must_be_unique()
    {
        $category = Category::factory()->create();
        Product::factory()->create(['sku' => 'DUP-001', 'category_id' => $category->id]);

        $response = $this->post('/products', [
            'sku'            => 'DUP-001',
            'name'           => 'Duplicate SKU Product',
            'category_id'    => $category->id,
            'purchase_price' => 10000,
            'selling_price'  => 15000,
            'minimum_stock'  => 5,
            'unit'           => 'pcs',
        ]);

        $response->assertSessionHasErrors('sku');
    }

    /** @test */
    public function product_requires_valid_category()
    {
        $response = $this->post('/products', [
            'sku'            => 'TST-002',
            'name'           => 'Test Product',
            'category_id'    => 99999, // Non-existent
            'purchase_price' => 10000,
            'selling_price'  => 15000,
            'minimum_stock'  => 5,
            'unit'           => 'pcs',
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function product_current_stock_always_starts_at_zero()
    {
        $category = Category::factory()->create();

        $this->post('/products', [
            'sku'            => 'TST-003',
            'name'           => 'Test Stock Zero',
            'category_id'    => $category->id,
            'purchase_price' => 10000,
            'selling_price'  => 15000,
            'minimum_stock'  => 5,
            'unit'           => 'pcs',
        ]);

        $product = Product::where('sku', 'TST-003')->first();
        $this->assertEquals(0, $product->current_stock);
    }

    /** @test */
    public function low_stock_status_is_correct()
    {
        $product = Product::factory()->create([
            'current_stock' => 5,
            'minimum_stock' => 10,
        ]);

        $this->assertEquals('low_stock', $product->stock_status);
        $this->assertEquals('Stok Menipis', $product->stock_status_label);
    }

    /** @test */
    public function out_of_stock_status_is_correct()
    {
        $product = Product::factory()->outOfStock()->create([
            'minimum_stock' => 10,
        ]);

        $this->assertEquals('out_of_stock', $product->stock_status);
        $this->assertEquals('Habis', $product->stock_status_label);
    }

    /** @test */
    public function available_status_is_correct()
    {
        $product = Product::factory()->create([
            'current_stock' => 50,
            'minimum_stock' => 10,
        ]);

        $this->assertEquals('available', $product->stock_status);
        $this->assertEquals('Tersedia', $product->stock_status_label);
    }

    /** @test */
    public function product_with_movements_cannot_be_deleted()
    {
        $product = Product::factory()->withStock(10)->create();
        $admin = User::factory()->admin()->create();

        \App\Models\StockMovement::factory()->create([
            'product_id'       => $product->id,
            'user_id'          => $admin->id,
            'type'             => 'in',
            'quantity'         => 10,
            'reference_number' => 'IN-TEST-00001',
            'occurred_at'      => now(),
            'stock_before'     => 0,
            'stock_after'      => 10,
        ]);

        $response = $this->delete("/products/{$product->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    /** @test */
    public function product_negative_prices_are_rejected()
    {
        $category = Category::factory()->create();

        $response = $this->post('/products', [
            'sku'            => 'TST-004',
            'name'           => 'Negative Price Product',
            'category_id'    => $category->id,
            'purchase_price' => -1000,
            'selling_price'  => 15000,
            'minimum_stock'  => 5,
            'unit'           => 'pcs',
        ]);

        $response->assertSessionHasErrors('purchase_price');
    }
}
