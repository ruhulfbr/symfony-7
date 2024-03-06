<?php

// Src/Service/Php8.php
namespace App\Service;

use App\Attribute\HasAccess;

class Php8
{
    public function __construct()
    {

    }

    public function index(): void
    {
        $this->getResourceId();
        $this->getDebugType();
        $this->pregLastErrorMsg();
        $this->consistentTypeError();
        $this->sanerComparision();
        $this->weakMap();
        $this->nullsafeOperator();
        $this->checkAttribute();
        $this->matchExpression(5);
        $this->matchExpression('2');
        $this->namedArgs(value2: "value2", value: "value");
        $this->unionTypes(2);
        $this->unionTypes('ami');
        $this->arrayNegetiveIndex();
    }


    public function getResourceId(): mixed
    {
        echo "<pre> Get Resource Id" . PHP_EOL;

//        $fileHandle = fopen("notes.txt", "r");
//        $resourceId = get_resource_id($fileHandle);
//        var_dump($resourceId);

        return true;
    }

    public function getDebugType(): mixed
    {
        echo "<pre> Get Debug type" . PHP_EOL;

        $float = 1.235;
        var_dump(gettype($float));
        var_dump(get_debug_type($float));

        return true;
    }

    public function pregLastErrorMsg(): mixed
    {
        echo "<pre> Preg Last error Message" . PHP_EOL;

        preg_match('/(?:\D+|<\d+>)*[!?]/', 'Brayden Austin Diego');
        var_dump(preg_last_error_msg());

        return true;
    }

    public function consistentTypeError(): mixed
    {
        echo "<pre> Consistenet Type Error." . PHP_EOL;

        // strlen([]);
        // array_chunk([], -1);

        return true;
    }

    public function sanerComparision(): mixed
    {
        echo "<pre> Saner Comparison." . PHP_EOL;

        var_dump(0 == "foobar");
        var_dump("14" == 016);
        var_dump(0 == "0f");

        $validValues = ["foo", "bar", "baz"];
        $value = 0;
        var_dump(in_array($value, $validValues));

        return true;
    }

    public function weakMap(): mixed
    {
        echo "<pre> Weak Map." . PHP_EOL;

        $map = new \WeakMap;
        $data = new \stdClass();
        $map[$data] = 42;

        echo "<pre>";
        print_r($map);

        unset($data);
        print_r($map);


        var_dump($map);
        return $map;
    }

    public function nullsafeOperator(): mixed
    {
        echo "<pre> Null safe Operator." . PHP_EOL;

        $data = new \stdClass();
        $data->user = new \stdClass();
        $data->user->address = new \stdClass();
        $data->user->address->country = 'Bangladesh';

        $country = $data?->user?->address?->country;

        var_dump($country);
        return $country;
    }

    #[HasAccess(HasAccess::PUBLIC)]
    public function checkAttribute(): void
    {
        echo "<pre> Make a Custom Attribute and use." . PHP_EOL;
    }

    public function matchExpression(int|string $value): int|string
    {
        echo "<pre> Named Arguments " . PHP_EOL;
        $statusText = match ($value) {
            1 => 'Active',
            "2" => 'Inactive',
            3 => 2584,
            'four' => 4444,
            5 => 5555,
            6 => 6666,
        };

        var_dump($statusText);

        return $statusText;
    }

    public function namedArgs(int|string $value, int|string $value2,): int|string
    {
        echo "<pre> Named Arguments " . PHP_EOL;
        var_dump($value);
        var_dump($value2);
        return $value;
    }

    public function unionTypes(int|string $value): int|string
    {
        echo "<pre> Union type " . PHP_EOL;
        var_dump($value);
        return $value;
    }

    public function arrayNegetiveIndex(): void
    {
        echo "<pre> Negetive Index " . PHP_EOL;
        $a = array_fill(-5, 4, true);
        var_dump($a);
    }
}