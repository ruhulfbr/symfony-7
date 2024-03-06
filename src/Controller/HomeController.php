<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use App\Service\CSVReader;
use App\Service\Php8;


class HomeController extends AbstractController
{
    private string $_VIEW_PATH = "dashboard/";

    public function __construct(private readonly Php8 $php8)
    {

    }

    public function index(): Response
    {

        $result = $this->php8->index();

        echo "<pre>";
        print_r($result);
        exit();

        $number = random_int(100, 1000);

        return $this->render($this->_VIEW_PATH . 'index.html.twig', [
            'number' => $number,
            'page' => 'Dashboard'
        ]);
    }

    public function number(CSVReader $CSVReader): Response
    {
        $number = random_int(100, 1000);

        return $this->render($this->_VIEW_PATH . 'index.html.twig', [
            'number' => $number,
            'page' => 'Home'
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
        return $this->render($this->_VIEW_PATH . 'index.html.twig', [
            'number' => $number,
            'page' => 'Page'
        ]);
    }
}