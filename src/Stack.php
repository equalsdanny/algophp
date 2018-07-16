<?php

interface Stack
{
    public function push($value);
    public function pop();
    public function size();
}