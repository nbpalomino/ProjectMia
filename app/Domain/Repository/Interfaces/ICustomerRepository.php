<?php namespace App\Domain\Repository\Interfaces;

interface ICustomerRepository extends IRepository {
    public function getEnabled($pageIndex, $pageCount);
}