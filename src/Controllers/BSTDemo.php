<?php

namespace App\Controllers;

use App\BinarySearchTree;
use Symfony\Component\HttpFoundation\Response;

class BSTDemo
{
    public function index()
    {
        $session = [];

        $tree = new BinarySearchTree();
        //$session[] = $tree->state();

        $tree->add(1);
        $session[] = $tree->state();

        $tree->add(3);
        $session[] = $tree->state();

        $tree->add(2);
        $session[] = $tree->state();

        $tree->add(0);
        $session[] = $tree->state();

        $response = new Response(json_encode($session));
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}