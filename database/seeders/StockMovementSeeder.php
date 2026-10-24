<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    /**
     * Create realistic historical stock movements for dashboard chart demo.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $staff = User::where('role', 'staff')->first();

        if (!$admin || !$staff) {
            return;
        }

        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        // Generate movements for the past 30 days
        for ($daysAgo = 30; $daysAgo >= 1; $daysAgo--) {
            $date = now()->subDays($daysAgo);

            // Generate 2-5 movements per day
            $count = rand(2, 5);
            for ($i = 0; $i < $count; $i++) {
                $product = $products->random();
                $user = rand(0, 1) ? $admin : $staff;
                $type = rand(0, 2) > 0 ? 'in' : 'out'; // More ins than outs

                $qty = rand(1, 10);

                // Ensure we don't go negative in seeder
                if ($type === 'out' && $product->current_stock < $qty) {
                    continue;
                }

                $prefix = $type === 'in' ? 'IN' : 'OUT';
                $dateStr = $date->format('Ymd');
                $existing = StockMovement::where('reference_number', 'like', "{$prefix}-{$dateStr}-%")->count();
                $refNum = "{$prefix}-{$dateStr}-" . str_pad($existing + 1, 5, '0', STR_PAD_LEFT);

                // Skip if reference already exists
                if (StockMovement::where('reference_number', $refNum)->exists()) {
                    continue;
                }

                $stockBefore = $product->current_stock;
                $stockAfter = $type === 'in' ? $stockBefore + $qty : $stockBefore - $qty;

                // Make sure we don't go negative
                if ($stockAfter < 0) {
                    continue;
                }

                StockMovement::create([
                    'reference_number' => $refNum,
                    'product_id'       => $product->id,
                    'user_id'          => $user->id,
                    'type'             => $type,
                    'quantity'         => $qty,
                    'unit_cost'        => $type === 'in' ? $product->purchase_price : null,
                    'stock_before'     => $stockBefore,
                    'stock_after'      => $stockAfter,
                    'notes'            => null,
                    'occurred_at'      => $date->copy()->setTime(rand(8, 17), rand(0, 59)),
                ]);

                // Update product stock in memory for next iteration
                $product->current_stock = $stockAfter;
            }
        }
    }
}
