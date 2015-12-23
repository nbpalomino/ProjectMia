<?php namespace App\Domain\Repository\Interfaces;

interface IAccountRepository extends IRepository {
    public function findByNumber($accountNumber);
}