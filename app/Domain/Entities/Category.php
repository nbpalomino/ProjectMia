<?php namespace Domain\Entities;

class Category {

    protected $categoryId;
    protected $name;
    protected $available;

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

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

    /**
     * @return mixed
     */
    public function isAvailable()
    {
        return $this->available;
    }

    /**
     * @param mixed
     */
    public function available()
    {
        $this->available = true;
    }

    /**
     * @param mixed
     */
    public function unavailable()
    {
        $this->available = false;
    }
}