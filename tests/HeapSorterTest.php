<?php

class HeapSorterTest extends SortingTest
{
    /**
     * @dataProvider nodeAncestryData
     */
    public function testLeftChild($i, $left, $right, $parent)
    {
        $this->assertEquals($left, HeapSorter::hleft($i));
    }

    /**
     * @dataProvider nodeAncestryData
     */
    public function testRightChild($i, $left, $right, $parent)
    {
        $this->assertEquals($right, HeapSorter::hright($i));
    }

    /**
     * @dataProvider nodeAncestryData
     */
    public function testParent($i, $left, $right, $parent)
    {
        $this->assertEquals($parent, HeapSorter::hparent($i));
    }

    public function nodeAncestryData()
    {
        return [
            [0, 1, 2, null],
            [1, 3, 4, 0],
            [2, 5, 6, 0],
            [3, 7, 8, 1],
            [4, 9, 10, 1]
        ];
    }

    /**
     * @dataProvider heapifyData
     * @param $arrayBefore
     * @param $i
     * @param $arrayAfter
     */
    public function testHeapify($arrayBefore, $i, $arrayAfter)
    {
        $this->assertEquals($arrayAfter, HeapSorter::heapify($arrayBefore, $i));
    }

    public function heapifyData()
    {
        return [
            [[1, 2, 3], 0, [3, 2, 1]],
            [[3, 2, 1], 0, [3, 2, 1]],
            [[1, 1, 1], 0, [1, 1, 1]],
            [[1], 0, [1]],

            // checks that the root's right child holds max-heapify with its children
            // after the root node is swapped with its right child
            [[4, 5, 6, 1, 1, 5, 2], 0, [6, 5, 5, 1, 1, 4, 2]]
        ];
    }

    /**
     * @dataProvider isHeapData
     * @param $heap
     * @param $isHeap
     */
    public function testIsHeap($heap, $isHeap)
    {
        $this->assertEquals($isHeap, HeapSorter::isHeap($heap));
    }

    public function isHeapData()
    {
        return [
            [[1, 2, 3], false],
            [[3, 2, 1], true],
            [[1, 1, 1], true],
            [[1], true],
            [[], true],
            [[5, 4, 3, 4, 5, 2, 1], false],
            [[5, 4, 3, 2, 1, 2, 1], true]
        ];
    }

    /**
     * @dataProvider messyHeapsData
     * @param $arr
     */
    public function testBuild($arr)
    {
        $this->assertTrue(HeapSorter::isHeap(HeapSorter::build($arr)));
    }

    public function messyHeapsData()
    {
        return [
          [[1, 2, 3, 4, 5]],
          [[]],
          [[1]]
        ];
    }

    /**
     * @return Sorter
     */
    protected function newSorter()
    {
        return new HeapSorter();
    }
}