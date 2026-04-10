<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'batch_number', 'expiry_date', 'cost_price', 'selling_price'];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getQuantityAttribute()
    {
        return $this->stockMovements()->selectRaw('SUM(CASE WHEN type = "IN" THEN quantity WHEN type = "OUT" THEN -quantity ELSE quantity END) as total')->value('total') ?? 0;
    }

    public function scopeAvailable($query)
    {
        // This is tricky without a static quantity column. 
        // For now, we'll order by expiry.
        return $query->where('expiry_date', '>', now())->orderBy('expiry_date', 'asc');
    }
}
