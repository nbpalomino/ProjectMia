<?php namespace App\Domain\Repository\Interfaces;

interface IProductRepository extends IRepository {
    public function getAvailable();
    public function getByBrand($brand);
}