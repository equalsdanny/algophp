<?php

class ArrayStack implements Stack
{
    private $array;

    /**
     * ArrayStack constructor.
     */
    public function __construct()
    {
        $this->array = [];
    }

    /**
     * @param $value
     */
    public function push($value)
    {
        $this->array[] = $value;
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        if($this->size() == 0) {
            throw new LengthException("Illegal pop() on empty stack");
        }

        return array_pop($this->array);
    }

    public function size()
    {
        return count($this->array);
    }
}