<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'barcode', 'base_price', 'quantity', 'category_id', 'sync_status'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function getQuantityAttribute()
    {
        return $this->stockMovements()->selectRaw('SUM(CASE WHEN type = "IN" THEN quantity WHEN type = "OUT" THEN -quantity ELSE quantity END) as total')->value('total') ?? 0;
    }
}
