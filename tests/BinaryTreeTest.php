<?php

class BinaryTreeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider randomArrays
     * @param $unsorted
     */
    public function testAddThenSortedArray($unsorted)
    {
        $tree = new BinarySearchTree();
        foreach($unsorted as $value) {
            $tree->add($value);
        }

        $sortedWithPhp = $unsorted;
        sort($sortedWithPhp);

        $sortedWithAlgo = $tree->toSortedArray();

        $this->assertEquals($sortedWithPhp, $sortedWithAlgo);
    }

    /**
     * @dataProvider randomArrays
     * @param $array
     */
    public function testAddThenHas($array)
    {
        $tree = new BinarySearchTree();
        $shadow = [];

        foreach($array as $value) {
            // Checking before insertion
            $this->assertEquals(in_array($value, $shadow), $tree->has($value));

            // Insertion
            $tree->add($value);
            $shadow[] = $value;

            // Checking after insertion
            $this->assertEquals(in_array($value, $shadow), $tree->has($value));
        }
    }

    /**
     * @dataProvider randomArrays
     * @param $array
     */
    public function testAddThenMinimumAndMaximum($array)
    {
        $tree = new BinarySearchTree();

        if(count($array) == 0) {
            $this->expectException(LengthException::class);
            $tree->minimum();

            // we should not get here
            $this->fail();
        }

        // sorting to find the smallest and the largest value
        sort($array);

        $smallest = $array[0];
        $largest = $array[count($array)-1];

        foreach($array as $value) {
            $tree->add($value);
        }

        $this->assertEquals($smallest, $tree->minimum());
        $this->assertEquals($largest, $tree->maximum());
    }

    /**
     * @dataProvider randomArrays
     */
    public function testAddManyThenRemoveManyWithHas($array)
    {
        $tree = new BinarySearchTree();

        // For testing has(), we need the array to be unique
        $array = array_unique($array);

        // Inserting items
        foreach($array as $item) {
            $this->assertFalse($tree->has($item));
            $tree->add($item);
            $this->assertTrue($tree->has($item));
        }

        // Removing items
        foreach($array as $item) {
            $this->assertTrue($tree->has($item));
            $tree->delete($item);
            $this->assertFalse($tree->has($item));
        }
    }

    /**
     * Generates 100 arrays with random quantity of integers between -100 and 100.
     * Seeded for consistency between runs.
     *
     * @return array
     */
    public function randomArrays()
    {
        srand(42);
        $arrays = [];

        for($i = 0;$i < 100;$i++) {
            $length = rand(0, 100);
            $unsorted = [];

            for($j = 0;$j < $length;$j++) {
                $value = rand(-100,100);

                $unsorted[] = $value;
            }

            // Enclosing $unsorted as array because PHPUnit expects array of arguments for each case
            $arrays[] = [$unsorted];
        }

        return $arrays;
    }

}