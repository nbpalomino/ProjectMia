<?php namespace Domain\ValueObjects;

class Brand {

    protected $name;
    protected $initials;
    protected $model;
    protected $country;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}