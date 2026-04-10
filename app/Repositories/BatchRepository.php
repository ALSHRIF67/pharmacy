<?php

namespace App\Repositories;

use App\Models\Batch;

class BatchRepository
{
    public function getAvailableBatches(int $productId)
    {
        return Batch::where('product_id', $productId)
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date', 'asc')
            ->get()
            ->filter(function ($batch) {
                return $batch->quantity > 0;
            });
    }

    public function findById(int $id)
    {
        return Batch::find($id);
    }

    public function create(array $data)
    {
        return Batch::create($data);
    }

    public function update(int $id, array $data)
    {
        $batch = Batch::findOrFail($id);
        $batch->update($data);
        return $batch;
    }
}
