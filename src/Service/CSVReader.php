<?php

// Src/Service/CSVReader.php

namespace App\Service;

class CSVReader
{
    private string $_filePath;

    private string $publicDirectory;

    public function __construct(string $publicDirectory)
    {
        $this->publicDirectory = $publicDirectory;
    }

    public function read(string $_filePath): object
    {
        $this->_filePath = $this->publicDirectory . '/' . $_filePath;
        $result = $this->parse();


//        if (!empty($result->users)) {
//            foreach ($result->users as $user) {
//                $result->users[] = $user;
//            }
//        }

        return $result;
    }

    private function parse(): object
    {
        $res = new \stdClass();
        $res->type = 'error';

        if (!file_exists($this->_filePath)) {
            $res->message = 'File not found';
            return $res;
        }

        try {
            $res->type = 'ok';
            $res->message = 'OK';
            $res->users = [];

            // Using the generator
            foreach ($this->readUsers() as $user) {
                $res->users[] = $user;
            }

        } catch (\Exception $e) {
            $res->message = $e->getMessage();
        }

        return $res;
    }

    private function readUsers(): \Generator
    {
        $handle = fopen($this->_filePath, 'r');

        $skip = false;
        while (($data = fgetcsv($handle)) !== false) {
            if (!$skip) {
                $skip = true;
                continue;
            }

            yield [
                'id' => $data[0],
                'name' => $data[1],
                'age' => $data[2],
            ];
        }

        fclose($handle);
    }


}