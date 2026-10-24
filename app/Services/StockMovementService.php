<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementService
{
    /**
     * Record incoming stock (barang masuk).
     *
     * @param  array{product_id: int, quantity: int, unit_cost: ?float, notes: ?string, occurred_at: ?string}  $data
     * @throws \Illuminate\Validation\ValidationException
     */
    public function recordIncoming(array $data): StockMovement
    {
        $this->validateQuantity($data['quantity']);

        return DB::transaction(function () use ($data) {
            /** @var Product $product */
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            $stockBefore = $product->current_stock;
            $stockAfter = $stockBefore + (int) $data['quantity'];

            $movement = StockMovement::create([
                'reference_number' => $this->generateReferenceNumber('IN'),
                'product_id'       => $product->id,
                'user_id'          => Auth::id(),
                'type'             => 'in',
                'quantity'         => (int) $data['quantity'],
                'unit_cost'        => isset($data['unit_cost']) && $data['unit_cost'] !== '' ? $data['unit_cost'] : null,
                'stock_before'     => $stockBefore,
                'stock_after'      => $stockAfter,
                'notes'            => $data['notes'] ?? null,
                'occurred_at'      => $data['occurred_at'] ?? now(),
            ]);

            $product->update(['current_stock' => $stockAfter]);

            return $movement;
        });
    }

    /**
     * Record outgoing stock (barang keluar).
     *
     * @param  array{product_id: int, quantity: int, notes: ?string, occurred_at: ?string}  $data
     * @throws \Illuminate\Validation\ValidationException
     */
    public function recordOutgoing(array $data): StockMovement
    {
        $this->validateQuantity($data['quantity']);

        return DB::transaction(function () use ($data) {
            /** @var Product $product */
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            $qty = (int) $data['quantity'];
            $stockBefore = $product->current_stock;

            // Enforce non-negative stock rule
            if ($stockBefore < $qty) {
                throw ValidationException::withMessages([
                    'quantity' => [
                        "Stok tidak mencukupi. Stok tersedia: {$stockBefore} {$product->unit}."
                    ],
                ]);
            }

            $stockAfter = $stockBefore - $qty;

            $movement = StockMovement::create([
                'reference_number' => $this->generateReferenceNumber('OUT'),
                'product_id'       => $product->id,
                'user_id'          => Auth::id(),
                'type'             => 'out',
                'quantity'         => $qty,
                'unit_cost'        => null,
                'stock_before'     => $stockBefore,
                'stock_after'      => $stockAfter,
                'notes'            => $data['notes'] ?? null,
                'occurred_at'      => $data['occurred_at'] ?? now(),
            ]);

            $product->update(['current_stock' => $stockAfter]);

            return $movement;
        });
    }

    /**
     * Record a stock adjustment (penyesuaian stok).
     * Creates an auditable adjustment movement.
     *
     * @param  array{product_id: int, new_stock: int, notes: string}  $data
     */
    public function recordAdjustment(array $data): StockMovement
    {
        if ($data['new_stock'] < 0) {
            throw ValidationException::withMessages([
                'new_stock' => ['Jumlah stok tidak boleh negatif.'],
            ]);
        }

        return DB::transaction(function () use ($data) {
            /** @var Product $product */
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            $stockBefore = $product->current_stock;
            $stockAfter = (int) $data['new_stock'];
            $qty = abs($stockAfter - $stockBefore);

            $movement = StockMovement::create([
                'reference_number' => $this->generateReferenceNumber('ADJ'),
                'product_id'       => $product->id,
                'user_id'          => Auth::id(),
                'type'             => 'adjustment',
                'quantity'         => $qty,
                'unit_cost'        => null,
                'stock_before'     => $stockBefore,
                'stock_after'      => $stockAfter,
                'notes'            => $data['notes'] ?? 'Penyesuaian stok',
                'occurred_at'      => now(),
            ]);

            $product->update(['current_stock' => $stockAfter]);

            return $movement;
        });
    }

    /**
     * Validate that quantity is a positive integer.
     */
    private function validateQuantity(mixed $quantity): void
    {
        $qty = (int) $quantity;
        if ($qty <= 0) {
            throw ValidationException::withMessages([
                'quantity' => ['Jumlah harus lebih dari 0.'],
            ]);
        }
    }

    /**
     * Generate a unique reference number in format PREFIX-YYYYMMDD-XXXXX.
     */
    private function generateReferenceNumber(string $prefix): string
    {
        $date = now()->format('Ymd');
        $base = "{$prefix}-{$date}-";

        // Find the highest existing suffix for today
        $last = StockMovement::where('reference_number', 'like', "{$base}%")
            ->orderByDesc('reference_number')
            ->value('reference_number');

        if ($last) {
            $lastNum = (int) substr($last, strrpos($last, '-') + 1);
            $next = str_pad($lastNum + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $next = '00001';
        }

        return "{$base}{$next}";
    }
}
