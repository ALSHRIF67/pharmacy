<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\BatchRepository;
use App\Repositories\StockRepository;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected $productRepo;
    protected $batchRepo;
    protected $inventoryService;

    public function __construct(
        ProductRepository $productRepo,
        BatchRepository $batchRepo,
        InventoryService $inventoryService
    ) {
        $this->productRepo = $productRepo;
        $this->batchRepo = $batchRepo;
        $this->inventoryService = $inventoryService;
    }

    public function getByBarcode(string $barcode)
    {
        return $this->productRepo->findByBarcode($barcode);
    }

    public function getAllPaginated($perPage = 10)
    {
        return $this->productRepo->model::with(['category', 'batches'])->paginate($perPage);
    }

    public function createProduct(array $data)
    {
        return $this->productRepo->create($data);
    }

    public function updateProduct($id, array $data)
    {
        return $this->productRepo->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->productRepo->delete($id);
    }

    public function updateProductAndStock(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepo->updateByBarcode($data['barcode'], [
                'name' => $data['name'],
                'base_price' => $data['price'],
                'category_id' => $data['category_id'] ?? null,
            ]);

            $batch = $this->batchRepo->create([
                'product_id' => $product->id,
                'batch_number' => $data['batch'],
                'expiry_date' => $data['expiry'],
                'cost_price' => $data['cost_price'] ?? 0,
                'selling_price' => $data['price'],
            ]);

            if (isset($data['stock']) && $data['stock'] > 0) {
                $this->inventoryService->addStock(
                    $product->id,
                    $batch->id,
                    $data['stock'],
                    'Initial stock from barcode scan'
                );
            }

            return $product;
        });
    }
}
