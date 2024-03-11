<?php

// Src/Service/Php82.php
namespace App\Service;

use SensitiveParameter;
use function Symfony\Component\DependencyInjection\Loader\Configurator\closure;

class Php82
{
    use Foo;

    public function __construct()
    {
    }

    public function index(): void
    {
        echo "<h1> Practicing PHP8.2 </h1>" . PHP_EOL;
        echo "<pre>";

        $this->sensitivePropertise();

    }

    public function constantInTrait(): void
    {
        echo PHP_EOL . "<br>Allow Constants in Trais" . PHP_EOL;

        echo $this->doFoo(1);
    }

    public function sensitivePropertise(): void
    {
        echo PHP_EOL . "<br>Sensitive Properties" . PHP_EOL;

        try {
            $this->getPayments('dj8gd4sl05db32xjch7989dsxws');
        } catch (\Exception $e) {
            var_dump($e->getTrace());
        }
    }


    private function getPayments(
        #[SensitiveParameter] string $apiKey
    ): array
    {
        throw new \Exception('Could not fetch payments');
    }


}