<?php

class HeapSorter implements Sorter
{
    public static function hleft($i)
    {
        return $i * 2 + 1;
    }

    public static function hright($i)
    {
        return ($i + 1) * 2;
    }

    public static function hparent($i)
    {
        $index = floor(($i-1) / 2);

        if($index < 0)
        {
            return null;
        }

        return $index;
    }

    /**
     * Ensures that max-heap property holds between $i node and its children.
     *
     * @param $arr
     * @param $i
     * @param null $n
     * @return array
     */
    public static function heapify(&$arr, $i, $n=null)
    {
        if($n === null) {
            // Defaulting to heapifying the whole array
            $n = count($arr);
        }

        $left = HeapSorter::hleft($i);
        $right = HeapSorter::hright($i);

        $leftExistsAndIsLargerThanCurrent = $left < $n && $arr[$left] > $arr[$i];
        $rightExistsAndIsLargerThenLeft = $right < $n && $arr[$right] > $arr[$left];
        $rightExistsAndIsLargerThenCurrent = $right < $n && $arr[$right] > $arr[$i];

        if($leftExistsAndIsLargerThanCurrent && !$rightExistsAndIsLargerThenLeft) {
            // swapping i and left
            $t = $arr[$i];
            $arr[$i] = $arr[$left];
            $arr[$left] = $t;
            HeapSorter::heapify($arr, $left, $n);
        } else if($rightExistsAndIsLargerThenCurrent) {
            // swapping i and right
            $t = $arr[$i];
            $arr[$i] = $arr[$right];
            $arr[$right] = $t;
            HeapSorter::heapify($arr, $right, $n);
        }

        return $arr;
    }

    public static function build(&$arr)
    {
        if(count($arr) < 2) {
            // any array with n < 2 is already a max-heap
            // also, the code below assumes that there is at least one parent
            return $arr;
        }

        $lastNode = count($arr) - 1;
        for($i = HeapSorter::hparent($lastNode);$i >= 0;$i--)
        {
            HeapSorter::heapify($arr, $i);
        }

        return $arr;
    }

    public static function isHeap($arr)
    {
        $n = count($arr);
        $lastNode = HeapSorter::hparent($n-1);

        for($i = 0;$i <= $lastNode;$i++)
        {
            $leftExistsAndIsLarger = HeapSorter::hleft($i) < $n && $arr[HeapSorter::hleft($i)] > $arr[$i];
            $rightExistsAndIsLarger = HeapSorter::hright($i) < $n && $arr[HeapSorter::hright($i)] > $arr[$i];
            if($leftExistsAndIsLarger || $rightExistsAndIsLarger) {
                return false;
            }
        }

        return true;
    }

    public function sort(&$arr)
    {
        HeapSorter::build($arr);

        $n = count($arr);

        for($i = $n-1;$i >= 0;$i--)
        {
            // Putting the largest node at the heap's tail
            $t = $arr[0];
            $arr[0] = $arr[$i];
            $arr[$i] = $t;

            // Contracting the heap at the tail
            $n--;

            // Ensuring that max-heap property holds
            HeapSorter::heapify($arr, 0, $n);
        }

        return $arr;
    }
}