<?php

// Loading classes
require_once './vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;


/*
 * ======================================
 * Routing
 * ======================================
 */
$routes = new RouteCollection();
$routes->add('hello', new Route('/', ['_controller' => ['App\\Controllers\\BSTDemo', 'index']]));

$matcher = new UrlMatcher($routes, new RequestContext());


/*
 * ======================================
 * Dispatcher
 * ======================================
 */
$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));


// create the Request object
$request = Request::createFromGlobals();


// create your controller and argument resolvers
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

// instantiate the kernel
$kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

// actually execute the kernel, which turns the request into a response
// by dispatching events, calling a controller, and returning the response
try {
    $response = $kernel->handle($request);
} catch (Exception $e) {
    var_dump($e);
    return true;
}

// send the headers and echo the content
$response->send();

// trigger the kernel.terminate event
$kernel->terminate($request, $response);

// php -S wants to see this script confirming that the output is valid
return true;