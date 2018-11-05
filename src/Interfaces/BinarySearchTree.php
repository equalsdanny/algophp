<?php

namespace App\Interfaces;

use Exception;
use Exceptions\ValueNotFoundException;
use Interfaces\BinaryTreeNode;

/**
 * Interface BinarySearchTree
 * @package App\Interfaces
 */
interface BinarySearchTree
{
    /**
     * @param $value
     * @return mixed
     */
    public function add($value);

    /**
     * @param $value
     * @return bool
     */
    public function has($value);

    /**
     * @param $value
     * @throws ValueNotFoundException
     * @throws Exception if the tree's state is inconsistent
     */
    public function delete($value);

    /**
     * @return mixed
     */
    public function minimum();

    /**
     * @return mixed
     */
    public function maximum();

    /**
     * @return BinaryTreeNode|null
     */
    public function root();

    /**
     * @return array
     */
    public function toSortedArray();
}