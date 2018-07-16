<?php

class InsertionSorter implements Sorter
{
    public function sort(&$arr)
    {
        for ($i = 1; $i < count($arr); $i++) {
            $hand = $arr[$i];

            for ($j = $i - 1; $j >= 0; $j--) {
                if ($arr[$j] > $arr[$j + 1]) {
                    $arr[$j + 1] = $arr[$j];
                    $arr[$j] = $hand;
                }
            }
        }

        return $arr;
    }
}