<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id', 
        'batch_id', 
        'type', 
        'quantity', 
        'reference_type', 
        'reference_id', 
        'created_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    protected static function booted()
    {
        static::created(function (self $movement) {
            $product = $movement->product()->first();
            if (! $product) return;

            if ($movement->type === 'IN') {
                $product->quantity = ($product->quantity ?? 0) + $movement->quantity;
            } else {
                $product->quantity = ($product->quantity ?? 0) - $movement->quantity;
            }
            $product->save();
        });
    }
}
