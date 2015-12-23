<?php namespace App\Domain\Entities;

class Customer {
    private $customerId;
    private $firstName;
    private $lastName;
    private $isEnabled;

    public function getCustomerId() {
        return $this->customerId;
    }

    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function isEnabled() {
        return $this->isEnabled;
    }

    public function setEnabled($isEnabled) {
        $this->isEnabled = $isEnabled;
}

    public function disable() {
        if ($this->isEnabled) {
            $this->isEnabled = false;
        }
    }

    public function enable() {
        if (!$this->isEnabled) {
            $this->isEnabled = true;
        }
    }

    public function getFullName() {
        return sprintf("%s, %s", $this->lastName, $this->firstName);
    }
}