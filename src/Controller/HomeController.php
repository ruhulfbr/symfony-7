<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class HomeController extends AbstractController
{
    private $_VIEW_PATH = "dashboard/";

    public function index(): Response
    {
        $number = random_int(100, 1000);

//        return $this->redirect('http://symfony.com/doc');

//        return $this->redirectToRoute('base.home', ['page' => 89]);

//        $response = $this->sendEarlyHints([
//            new Link(rel: 'preconnect', href: 'https://fonts.google.com'),
//            (new Link(href: '/style.css'))->withAttribute('as', 'stylesheet'),
//            (new Link(href: '/script.js'))->withAttribute('as', 'script'),
//        ]);
//
//        return $this->file('public/csv/sample-csv-100k.csv');
//
//        return $this->json(['username' => 'jane.doe']);


//        $contents = $this->render($this->_VIEW_PATH.'index.html.twig', [
//            'number' => $number,
//            'page'   => 'Dashboard'
//        ]);
//
//        echo $contents;

        return $this->render($this->_VIEW_PATH.'index.html.twig', [
            'number' => $number,
            'page'   => 'Dashboard'
        ]);
    }

    public function number(
        #[MapQueryParameter] string $firstName,
        #[MapQueryParameter] string $lastName,
        #[MapQueryParameter] int $age
    ): Response
    {
        $number = random_int(100, 1000);

        return $this->render($this->_VIEW_PATH.'index.html.twig', [
            'number' => $number,
            'page'   => 'Home'
        ]);
    }

    public function pageCheck(Request $request, int $page, LoggerInterface $logger): Response
    {
//        $routeName = $request->attributes->get('_route');
//        $routeParameters = $request->attributes->get('_route_params');
//        $allAttributes = $request->attributes->all();
//        echo "<pre>";
//        print_r($allAttributes);
//        exit();

        echo $this->generateUrl('base.home', ['page' => '20']);
//        exit();

        echo "<br>";

        try {
            $url = $this->generateUrl('base.sona', ['page' => '20']);
        } catch (RouteNotFoundException $e) {
            echo $e->getMessage();
        }

        $logger->info('We are logging!');


        $number = $page;
        return $this->render($this->_VIEW_PATH.'index.html.twig', [
            'number' => $number,
            'page'   => 'Page'
        ]);
    }
}