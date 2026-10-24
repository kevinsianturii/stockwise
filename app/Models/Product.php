<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'supplier_id',
        'description',
        'purchase_price',
        'selling_price',
        'current_stock',
        'minimum_stock',
        'unit',
        'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'current_stock' => 'integer',
        'minimum_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category this product belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier for this product (nullable).
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get all stock movements for this product.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the stock status label and style class.
     * 
     * Definitions:
     * - out_of_stock: current_stock === 0
     * - low_stock: current_stock > 0 AND current_stock <= minimum_stock
     * - available: current_stock > minimum_stock
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock === 0) {
            return 'out_of_stock';
        }

        if ($this->current_stock <= $this->minimum_stock) {
            return 'low_stock';
        }

        return 'available';
    }

    /**
     * Get human-readable stock status label in Indonesian.
     */
    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'out_of_stock' => 'Habis',
            'low_stock' => 'Stok Menipis',
            default => 'Tersedia',
        };
    }

    /**
     * Scope for active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for low-stock products (includes out-of-stock).
     */
    public function scopeLowStock($query)
    {
        return $query->where('current_stock', '<=', \DB::raw('minimum_stock'));
    }

    /**
     * Scope for out-of-stock products only.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', 0);
    }

    /**
     * Check if this product can be safely deleted (no movement history).
     */
    public function canBeDeleted(): bool
    {
        return $this->stockMovements()->count() === 0;
    }
}
