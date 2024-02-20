<?php

// src/Command/ParseUserDataFromCSV.php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:parse-user-data-from-csv',
    description: 'Parse User data from CSV and show total number of User and their avarage age.',
    hidden: false,
    aliases: ['app:parse-user-data']
)]
class ParseUserDataFromCSV extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Start parsing user data from CSV',
            '============',
            '',
        ]);

        $startTime = microtime(true);
        $startMemory = memory_get_peak_usage(true);

        $result = $this->parseCSV();

        $output->writeln("Status: ".$result->type);
        $output->writeln("Message: ".$result->message);

        if($result->type == 'ok'){
            $totalUsers = 0;
            $totalAge = 0;

            if( !empty($result->users)){
                foreach ($result->users as $user) {
                    $totalUsers++;
                    $totalAge += $user['age'];
                }
            }

            $averageAge = $totalUsers ? round($totalAge / $totalUsers) : 0;

            $output->writeln("Total Users: ".$totalUsers);
            $output->writeln("Avarage Age: ".$averageAge);

            $output->writeln('Operation Completed.');
        }
        else{
            $output->writeln('Operation Failed.');
        }

        $endTime = microtime(true);
        $endMemory = memory_get_peak_usage(true);

        $executionTime = round($endTime - $startTime, 4);
        $memoryUsage = round(($endMemory - $startMemory) / (1024 * 1024), 3);

        $output->writeln("\nExecution time: " . $executionTime . ' seconds');
        $output->writeln('Memory usage: ' . $memoryUsage . ' MB');

        return Command::SUCCESS;
    }

    private function parseCSV(): object
    {
        // $filePath = 'public/csv/sample-csv-1k.csv';
        // $filePath = 'public/csv/sample-csv-10k.csv';
        // $filePath = 'public/csv/sample-csv-100k.csv';
        $filePath = 'public/csv/sample-csv-1500k.csv';

        $res = new \stdClass();
        $res->type = 'error';
        $res->message = '';

        if (!file_exists($filePath)) {
            $res->message = 'File not found';
            return $res;
        }

        try {

            $res->type = 'ok';
            $res->message = 'OK';
            $res->users = $this->readUsers($filePath);

        } catch (\Exception $e) {
            $res->message = $e->getMessage();
        }

        return $res;
    }

    private function readUsers($filePath)
    {
        $handle = fopen($filePath, 'r');

        $skip = false;
        while(($data = fgetcsv($handle)) !== false){
            if(!$skip){
                $skip = true;
                continue;
            }

            yield [
                'id'   => $data[0],
                'name' => $data[1],
                'age'  => $data[2],
            ];
        }

        fclose($handle);
    }

}