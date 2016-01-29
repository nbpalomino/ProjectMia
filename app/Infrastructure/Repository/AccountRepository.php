<?php namespace Infrastructure\Repository;

use App\Domain\Repository\Interfaces\IAccountRepository;
use Illuminate\Support\Facades\DB;

class AccountRepository implements IAccountRepository {

    protected $conn;

    public function __construct(DB $connection)
    {
        $this->conn = $connection;
    }

    public function findByNumber($accountNumber)
    {
        // TODO: Implement findByNumber() method.
    }

    public function create($entity)
    {
        $this->conn->table('account')->insert([
            'number'    => $entity->getNumber(),
            'balance'   => $entity->getBalance(),
            'customer_id' => $entity->getCustomer()->getCustomerId(),
        ]);
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
        // TODO: Implement findById() method.
    }

    public function lists()
    {
        // TODO: Implement lists() method.
    }

    public function findAll()
    {
        // TODO: Implement findAll() method.
    }
}