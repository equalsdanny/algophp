<?php


class MergeSorter implements Sorter
{
    public function sort(&$arr)
    {
        $len = count($arr);

        if($len < 2)
        {
            return $arr;
        }

        // Divide
        $nleft = floor($len/2);
        $nright = $len - $nleft;
        $left = array_slice($arr, 0, $nleft);
        $right = array_slice($arr, $nleft, $nright);

        // Process
        $left = $this->sort($left);
        $right = $this->sort($right);

        // Merge
        $i = 0; // index of next available value in left array
        $j = 0; // index of next available value in right array

        $sorted = [];

        while(count($sorted) < $len) {
            if($i < $nleft && ($j == $nright || $left[$i] < $right[$j])) {
                $sorted[] = $left[$i++];
            } else {
                $sorted[] = $right[$j++];
            }
        }

        return $sorted;
    }
}