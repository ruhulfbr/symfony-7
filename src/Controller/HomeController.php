<?php

// src/Controller/HomeController.php
namespace App\Controller;

use App\Service\CSVReader;
use App\Service\Php8;
use App\Service\Php81;
use App\Service\Php82;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Ruhulfbr\CashApp\WebReceiptVerifier;

class HomeController extends AbstractController
{
    private string $_VIEW_PATH = "dashboard/";

    public function __construct(
        private readonly Php8  $php8,
        private readonly Php81 $php81,
        private readonly Php82 $php82
    )
    {

    }

    #[Route('/verify-cash-app-receipt', 'app_verify_cash_app_receipt')]
    public function verifyCashAppReceipt(): Response
    {
        $receipt = "https://cash.app/payments/3vzl4ytg1hcjyul5axcto89ju/receipt";  // (String) Required, CashApp Web receipt;
        $username = "your_cash_app_username"; // (String) Required, CashApp Account Username;
        $reference = "your_payment_reference"; // (String) Required, CashApp Payment Reference;

        // With Named argument
        // $cashApp = new WebReceiptVerifier(_USERNAME: $username, _REFERENCE: $reference);

        // Together
        $cashApp = new WebReceiptVerifier($username, $reference);

        echo "<pre>";
        print_r($cashApp->verify($receipt));
        exit();
    }


    #[Route('/php82', 'app_php82')]
    public function php82(): Response
    {

        $result = $this->php82->index();

        echo "<pre>";
        print_r($result);
        exit();

        $number = random_int(100, 1000);

        return $this->render($this->_VIEW_PATH . 'index.html.twig', [
            'number' => $number,
            'page' => 'Dashboard'
        ]);
    }

    #[Route('/php81', 'app_php81')]
    public function php81(): Response
    {

        $result = $this->php81->index();

        echo "<pre>";
        print_r($result);
        exit();

        $number = random_int(100, 1000);

        return $this->render($this->_VIEW_PATH . 'index.html.twig', [
            'number' => $number,
            'page' => 'Dashboard'
        ]);
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
        // $routeName = $request->attributes->get('_route');
        // $routeParameters = $request->attributes->get('_route_params');
        // $allAttributes = $request->attributes->all();
        // echo "<pre>";
        // print_r($allAttributes);
        // exit();

        echo $this->generateUrl('base.home', ['page' => '20']);
        // exit();

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