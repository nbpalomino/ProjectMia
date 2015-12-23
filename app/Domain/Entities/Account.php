<?php namespace App\Domain\Entities;

class Account {
    private $accountId;
    private $number;
    private $balance;
    private $locked;
    private $customer;

    public function getAccountId() {
        return $this->accountId;
    }

    public function setAccountId($accountId) {
        $this->accountId = $accountId;
    }

    public function isLocked() {
        return $this->locked;
    }

    public function setLocked($locked) {
        $this->locked = $locked;
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($pNumber) {
        $this->number = $pNumber;
    }

    public function getBalance() {
        return $this->balance;
    }

    public function setBalance($pBalance) {
        $this->balance = $pBalance;
    }

    public function getCustomer() {
        return $this->customer;
    }

    public function setCustomer(Customer $customer) {
        $this->customer = $customer;
    }

    public function lock() {
        if (!$this->locked) {
            $this->locked = true;
        }
    }

    public function unLock() {
        if ($this->locked) {
            $this->locked = false;
        }
    }

    public function canBeWithdrawed($pAmount) {
        return !$this->locked && ($this->balance >= $pAmount);
    }
}