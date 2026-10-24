<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'product_id',
        'user_id',
        'type',
        'quantity',
        'unit_cost',
        'stock_before',
        'stock_after',
        'notes',
        'occurred_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
        'occurred_at' => 'datetime',
    ];

    /**
     * Get the product associated with this movement.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who recorded this movement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the human-readable type label in Indonesian.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'in' => 'Barang Masuk',
            'out' => 'Barang Keluar',
            'adjustment' => 'Penyesuaian',
            default => ucfirst($this->type),
        };
    }

    /**
     * Scope to filter by movement type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for incoming movements.
     */
    public function scopeIncoming($query)
    {
        return $query->where('type', 'in');
    }

    /**
     * Scope for outgoing movements.
     */
    public function scopeOutgoing($query)
    {
        return $query->where('type', 'out');
    }

    /**
     * Scope for date range filtering.
     */
    public function scopeDateRange($query, $from, $to)
    {
        if ($from) {
            $query->whereDate('occurred_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('occurred_at', '<=', $to);
        }
        return $query;
    }
}
