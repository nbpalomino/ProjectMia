<?php namespace App\Domain\Repository\Interfaces;

interface IRepository {
    public function create($entity);
    public function update($entity);
    public function delete($id);
    public function findById($id);
    public function lists();
}