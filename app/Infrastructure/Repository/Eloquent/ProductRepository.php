<?php namespace App\Infrastructure\Repository\Eloquent;

use App\Domain\Repository\Interfaces\IProductRepository;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class ProductRepository implements IProductRepository {

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAvailable()
    {
        return $this->product->where('disponible', true)->get();
    }

    public function getByBrand($brand)
    {
        return $this->product->where('marca_id', $brand)->get();
    }

    public function create($entity)
    {
        // TODO: Implement create() method.
    }

    public function update($entity)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function findById($id)
    {
        return $this->product->find($id);
    }

    public function lists()
    {
        // TODO: Implement lists() method.
    }

    public function findAll()
    {
        return $this->product->all();
    }
}