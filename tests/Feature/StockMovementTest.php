<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\StockMovementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StockMovementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private StockMovementService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->service = new StockMovementService();
        $this->actingAs($this->admin);
    }

    /** @test */
    public function stock_in_increases_current_stock()
    {
        $product = Product::factory()->withStock(10)->create();

        $this->service->recordIncoming([
            'product_id' => $product->id,
            'quantity'   => 5,
        ]);

        $this->assertEquals(15, $product->fresh()->current_stock);
    }

    /** @test */
    public function stock_in_creates_movement_record()
    {
        $product = Product::factory()->withStock(0)->create();

        $movement = $this->service->recordIncoming([
            'product_id' => $product->id,
            'quantity'   => 20,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id'   => $product->id,
            'type'         => 'in',
            'quantity'     => 20,
            'stock_before' => 0,
            'stock_after'  => 20,
        ]);
    }

    /** @test */
    public function stock_out_decreases_current_stock()
    {
        $product = Product::factory()->withStock(20)->create();

        $this->service->recordOutgoing([
            'product_id' => $product->id,
            'quantity'   => 8,
        ]);

        $this->assertEquals(12, $product->fresh()->current_stock);
    }

    /** @test */
    public function stock_out_creates_movement_record()
    {
        $product = Product::factory()->withStock(20)->create();

        $this->service->recordOutgoing([
            'product_id' => $product->id,
            'quantity'   => 5,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id'   => $product->id,
            'type'         => 'out',
            'quantity'     => 5,
            'stock_before' => 20,
            'stock_after'  => 15,
        ]);
    }

    /** @test */
    public function stock_out_is_rejected_when_insufficient_stock()
    {
        $product = Product::factory()->withStock(3)->create();

        $this->expectException(ValidationException::class);

        $this->service->recordOutgoing([
            'product_id' => $product->id,
            'quantity'   => 10,
        ]);

        // Stock must remain unchanged
        $this->assertEquals(3, $product->fresh()->current_stock);
    }

    /** @test */
    public function stock_out_does_not_create_movement_when_insufficient()
    {
        $product = Product::factory()->withStock(5)->create();

        try {
            $this->service->recordOutgoing([
                'product_id' => $product->id,
                'quantity'   => 100,
            ]);
        } catch (ValidationException $e) {
            // Expected
        }

        $this->assertDatabaseMissing('stock_movements', [
            'product_id' => $product->id,
            'type'       => 'out',
        ]);

        // Stock unchanged
        $this->assertEquals(5, $product->fresh()->current_stock);
    }

    /** @test */
    public function zero_quantity_is_rejected_for_stock_in()
    {
        $product = Product::factory()->create();

        $this->expectException(ValidationException::class);

        $this->service->recordIncoming([
            'product_id' => $product->id,
            'quantity'   => 0,
        ]);
    }

    /** @test */
    public function negative_quantity_is_rejected_for_stock_in()
    {
        $product = Product::factory()->create();

        $this->expectException(ValidationException::class);

        $this->service->recordIncoming([
            'product_id' => $product->id,
            'quantity'   => -5,
        ]);
    }

    /** @test */
    public function zero_quantity_is_rejected_for_stock_out()
    {
        $product = Product::factory()->withStock(10)->create();

        $this->expectException(ValidationException::class);

        $this->service->recordOutgoing([
            'product_id' => $product->id,
            'quantity'   => 0,
        ]);
    }

    /** @test */
    public function stock_never_goes_negative()
    {
        $product = Product::factory()->withStock(5)->create();

        try {
            $this->service->recordOutgoing([
                'product_id' => $product->id,
                'quantity'   => 10,
            ]);
        } catch (ValidationException $e) {
            // Expected
        }

        $this->assertGreaterThanOrEqual(0, $product->fresh()->current_stock);
    }

    /** @test */
    public function stock_is_correct_after_multiple_movements()
    {
        $product = Product::factory()->withStock(0)->create();

        $this->service->recordIncoming(['product_id' => $product->id, 'quantity' => 50]);
        $this->service->recordOutgoing(['product_id' => $product->id, 'quantity' => 10]);
        $this->service->recordIncoming(['product_id' => $product->id, 'quantity' => 30]);
        $this->service->recordOutgoing(['product_id' => $product->id, 'quantity' => 20]);

        // 0 + 50 - 10 + 30 - 20 = 50
        $this->assertEquals(50, $product->fresh()->current_stock);
    }

    /** @test */
    public function reference_numbers_are_unique()
    {
        $product = Product::factory()->withStock(0)->create();

        $m1 = $this->service->recordIncoming(['product_id' => $product->id, 'quantity' => 5]);
        $m2 = $this->service->recordIncoming(['product_id' => $product->id, 'quantity' => 5]);
        $m3 = $this->service->recordIncoming(['product_id' => $product->id, 'quantity' => 5]);

        $this->assertNotEquals($m1->reference_number, $m2->reference_number);
        $this->assertNotEquals($m2->reference_number, $m3->reference_number);
    }

    /** @test */
    public function stock_movement_http_in_works()
    {
        $product = Product::factory()->withStock(10)->create();

        $response = $this->post('/stock-movements/in', [
            'product_id' => $product->id,
            'quantity'   => 5,
        ]);

        $response->assertRedirect();
        $this->assertEquals(15, $product->fresh()->current_stock);
    }

    /** @test */
    public function stock_movement_http_out_rejects_insufficient_stock()
    {
        $product = Product::factory()->withStock(3)->create();

        $response = $this->post('/stock-movements/out', [
            'product_id' => $product->id,
            'quantity'   => 10,
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertEquals(3, $product->fresh()->current_stock);
    }

    /** @test */
    public function movement_history_is_visible()
    {
        $product = Product::factory()->withStock(20)->create();

        $this->service->recordIncoming(['product_id' => $product->id, 'quantity' => 10]);

        $response = $this->get('/stock-movements');
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    /** @test */
    public function csv_export_is_accessible_and_returns_csv()
    {
        $response = $this->get('/stock-movements/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
