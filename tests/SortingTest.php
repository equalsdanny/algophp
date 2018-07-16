<?php

use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: danylo
 * Date: 6/19/18
 * Time: 10:25 AM
 */


abstract class SortingTest extends TestCase
{
    /**
     * @return Sorter
     */
    abstract protected function newSorter();

    /**
     * @dataProvider sortingProvider
     * @param $unsorted array
     * @param $sorted array
     */
    public function testSorting($unsorted, $sorted)
    {
        $sorter = $this->newSorter();
        $this->assertEquals($sorted, $sorter->sort($unsorted));
    }

    public function sortingProvider()
    {
        return [
            [[1, 3, 2], [1, 2, 3]],
            [[1, -1, 0], [-1, 0, 1]],
            [[], []],
            [[0], [0]],
            [[1, 6, 4, 3, 1, 3, 1], [1, 1, 1, 3, 3, 4, 6]]
        ];
    }

}
