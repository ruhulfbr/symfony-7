<?php

// Src/Service/Php81.php
namespace App\Service;

use function Symfony\Component\DependencyInjection\Loader\Configurator\closure;

class Php81
{
    //PHP 8.0, you can override class constants with its child classes, but if you use final keyword before const then it can not be overridden.
    final const HELLO = 'hello';

    public function __construct()
    {
    }

    public function index(): void
    {
        echo "<h1> Practicing PHP8.1 </h1>" . PHP_EOL;
        echo "<pre>";

        $this->firstClassCallAbleSyntax();
        $this->arrayUpackedSupportStringKeyedArrays();
        $this->intlDatePatternGenerator();
        $this->arrayIsList();

        // Readonly property
        // final Class Constants,
//        $this->fiber();
        // $this->neverReturnType();
        $this->useEnum(MyEnum::Clubs);
        $this->itersectionType(new Intersection());
        $this->reflectionClass();

    }

    public function firstClassCallAbleSyntax(): void
    {
        echo PHP_EOL . "<br>First-Class Callable Syntax" . PHP_EOL;

        $fn = \Closure::fromCallable('strlen');
        $fn = strlen(...);

        // $fn = \Closure::fromCallable('strlen');
        $fn = strtoupper(...);
        echo $fn('dfhidshkfh');
    }

    public function arrayUpackedSupportStringKeyedArrays(): void
    {
        echo PHP_EOL . "<br>Array Unpacking Support for String-Keyed Arrays " . PHP_EOL;

        $array1 = ["a" => 1];
        $array2 = ["a" => 2];
        $array3 = ["a" => 0x10];
        $array = ["a" => 0, ...$array2, ...$array1, ...$array3]; // Last array always win
        var_dump($array); // ["a" => 2]

    }

    public function intlDatePatternGenerator(): void
    {
        echo PHP_EOL . "<br>IntlDatePatternGenerator " . PHP_EOL;

        $skeleton = "YYYYMMdd";

        $today = \DateTimeImmutable::createFromFormat('Y-m-d', '2021-04-24');


        $dtpg = new \IntlDatePatternGenerator("de_DE");
        $pattern = $dtpg->getBestPattern($skeleton);
        echo "de: ", \IntlDateFormatter::formatObject($today, $pattern, "de_DE"), "\n";

        $dtpg = new \IntlDatePatternGenerator("en_US");
        $pattern = $dtpg->getBestPattern($skeleton) . "\n";
        echo "en: ", \IntlDateFormatter::formatObject($today, $pattern, "en_US"), "\n";

    }

    public function arrayIsList(): void
    {
        echo PHP_EOL . "<br>array_is_list() " . PHP_EOL;

        // true array_is_list() examples
        var_dump(array_is_list([])); // true
        var_dump(array_is_list([1, 2, 3])); // true
        var_dump(array_is_list(['cats', 2, 3])); // true
        var_dump(array_is_list(['cats', 'dogs'])); // true
        var_dump(array_is_list([0 => 'cats', 'dogs'])); // true
        var_dump(array_is_list([0 => 'cats', 1 => 'dogs'])); // true

        // false array_is_list() examples
        var_dump(array_is_list([1 => 'cats', 'dogs'])); // as first key isn't 0
        var_dump(array_is_list([1 => 'cats', 0 => 'dogs'])); // keys are out of order
        var_dump(array_is_list([0 => 'cats', 'bark' => 'dogs'])); // non-integer keys
        var_dump(array_is_list([0 => 'cats', 2 => 'dogs']));
    }

    public function fiber(): void
    {
        echo PHP_EOL . "<br>Fiber " . PHP_EOL;

        echo "Main thread started.\n";

        $fibers = [];

        // Create fibers for multiple asynchronous tasks.
        $fibers[] = new \Fiber(function () {
            $response = $this->fetchData("https://api.npoint.io/d96cfc702c24a2992c41");
            $value = \Fiber::suspend($response);
            print_r($value . "\n");
        });

        $fibers[] = new \Fiber(function () {
            $response = $this->fetchData("https://api.npoint.io/c116605fac1cb2b75fe9");
            $value = \Fiber::suspend($response);
            print_r($value . "\n");
        });

        $fibers[] = new \Fiber(function () {
            $response = $this->fetchData("https://api.npoint.io/327efbf48fbb524bef09");
            $value = \Fiber::suspend($response);
            print_r($value . "\n");
        });

        $captureResponseOnSuspension = [];

        // Start all fibers.
        foreach ($fibers as $i => $fiber) {
            echo "Fiber $i started.\n";
            $captureResponseOnSuspension[$i] = $fiber->start();
        }

        // Resume all fibers.
        foreach ($fibers as $i => $fiber) {
            echo "Fiber $i resumed.\n";
            $fiber->resume($captureResponseOnSuspension[$i]);
        }

        echo "Main thread done.\n";

    }

//    public function neverReturnType(): never
//    {
//        echo PHP_EOL . "<br>Never Return Type " . PHP_EOL;
//        // die();
//        // exit();
//        //return "dsds"; // It Generate a compile time error.
//    }

    public function useEnum(MyEnum $suit): void
    {
        echo PHP_EOL . "<br>Using Enum: " . $suit->value . PHP_EOL;
    }

    public function itersectionType(Countable&Traversable $countable): void
    {
        echo PHP_EOL . "<br>Intersection Types Class" . PHP_EOL;

        echo $countable->one();
        echo "</br>";
        echo $countable->two();
    }

    public function reflectionClass(): void
    {
        echo PHP_EOL . "<br>Relection Class" . PHP_EOL;

        $php8 = new Php8();
        $class = new \ReflectionClass($php8);
        // $methods = $class->getMethods();
        // var_dump($methods);

        // $methods = $class->getConstants();

        //  foreach ($methods as $method) {
        //  echo $method->getName() . '<br>';
        //  }

        $method = $class->getMethod('myPrivate');
        $method->setAccessible(TRUE);
        echo $method->invoke($php8, 95);

    }

    private function fetchData($url): mixed
    {
        return file_get_contents($url);
    }


}