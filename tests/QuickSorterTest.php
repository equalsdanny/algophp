<?php
/**
 * Created by PhpStorm.
 * User: danylo
 * Date: 6/21/18
 * Time: 11:22 AM
 */

class QuickSorterTest extends SortingTest
{

    /**
     * @dataProvider arraysProvider
     * @param $arr
     */
    public function testPartition($arr)
    {
        // Array dimensions
        $s = 0;
        $e = count($arr)-1;

        // Pivot index
        $p = QuickSorter::partition($arr, $s, $e);

        $this->assertTrue(QuickSorter::isPartitioned($arr, $s, $e, $p));
    }

    public function arraysProvider()
    {
        return [
            [[1, 2, 3, 4, 5]],
            [[1, 6, 3, 2, 4]],
            [[0, 0, 0, 0, 0]],
            [[3, 2]],
            [[1]]
        ];
    }

    /**
     * @return Sorter
     */
    protected function newSorter()
    {
        return new QuickSorter();
    }
}