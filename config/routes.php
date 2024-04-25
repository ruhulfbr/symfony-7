<?php

// config/routes.php
use App\Controller\HomeController;
use App\Controller\QueryMakerController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('query_maker', '/')
        ->controller([QueryMakerController::class, 'index'])
        ->methods(['GET', 'POST', 'HEAD']);

    $routes->add('dashboard', '/home2')
        ->controller([HomeController::class, 'index'])
        ->methods(['GET', 'HEAD']);

    $routes->add('home', '/home')
        ->controller([HomeController::class, 'number'])
        ->methods(['GET', 'HEAD']);

    //    $routes->add('base.home', '/base/{page}')
    //        ->controller([HomeController::class, 'pageCheck'])
    //        ->methods(['GET', 'HEAD'])
    //        ->defaults(['page' => 1])
    //        ->requirements(['page' => '\d+']);

//    This is similer to prev routes
    $routes->add('base.home', '/base/{page<\d+>?1}')
        ->controller([HomeController::class, 'pageCheck'])
        ->methods(['GET', 'HEAD']);

    $routes->alias('base.mill', 'base.home');

};

// if the action is implemented as the __invoke() method of the
// controller class, you can skip the 'method_name' part:
// ->controller(BlogController::class)