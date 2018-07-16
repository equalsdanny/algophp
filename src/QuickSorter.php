<?php

class QuickSorter implements Sorter
{

    public static function partition(&$arr, $s, $e)
    {
        $pivot = $arr[$e];

        $endOfSmaller = $s-1;

        for($i = $s;$i < $e;$i++) {
            // If we find value at the tail of LP that is smaller than pivot
            if($arr[$i] < $pivot) {
                // We need to swap it with the head of LP
                $t = $arr[$i];
                $arr[$i] = $arr[$endOfSmaller+1];
                $arr[$endOfSmaller+1] = $t;

                // And move the head of LP, so that the swapped value
                // is now at the tail of SP
                $endOfSmaller++;
            }
        }

        // Now we can swap the pivot with the head of LP
        $t = $arr[$endOfSmaller+1];
        $arr[$endOfSmaller+1] = $arr[$e];
        $arr[$e] = $t;

        // Returning the index of the pivot
        return $endOfSmaller+1;
    }

    public static function isPartitioned($arr, $s, $e, $p)
    {
        for($i = $s;$i <= $e;$i++) {
            // If we are in SP, but found a larger value
            if($i < $p && $arr[$i] > $arr[$p]) {
                return false;
            }

            // If we are in LP, but found a smaller value
            if($i > $p && $arr[$i] < $arr[$p]) {
                return false;
            }
        }

        // Did not find any value in the wrong partition
        return true;
    }

    public function sort(&$array, $s = null, $e = null)
    {
        if(count($array) < 2) {
            // Nothing to sort but code below assumes that there is
            return $array;
        }

        if($s === null) {
            $s = 0;
        }

        if($e === null) {
            $e = count($array)-1;
        }

        $p = QuickSorter::partition($array, $s, $e);

        // if SP is not empty
        if($p > $s) {
            QuickSorter::sort($array, $s, $p - 1);
        }

        // if LP is not empty
        if($p < $e) {
            QuickSorter::sort($array, $p + 1, $e);
        }

        return $array;
    }
}