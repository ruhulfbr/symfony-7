<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\QueryMakerCSV;
use App\Repository\QueryMakerCSVRepository;
use App\Form\QueryMakerType;


class QueryMakerController extends AbstractController
{
    public function index(Request $request, QueryMakerCSVRepository $queryMakerRepo, EntityManagerInterface $entityManager): Response
    {
        $queryMaker = new QueryMakerCSV();
        $form = $this->createForm(QueryMakerType::class, $queryMaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableName = $form->get('table_name')->getData();
            $create_query = $form->get('create_query')->getData();
            $csvFile = $form->get('csvFile')->getData();
            $csvData = $this->extractCSVData($csvFile);

            $error = "";
            if (empty($csvData['columns'])) {
                $error = "No columns found";
            }

            if (empty($csvData['rows'])) {
                $error = "No row data found to generate query";
            }

            if (empty($error)) {
                $message = "Query generated successfully";
                $queryData = $this->generateQuery($tableName, $csvData, $create_query);
            } else {
                $message = $error;
                $queryData = "";
            }

            return $this->render('query_maker/query.html.twig', [
                'message' => $message,
                'table_name' => $this->validateString($tableName),
                'query_data' => $queryData
            ]);
        }

        return $this->render('query_maker/index.html.twig', [
            'csv_list' => $queryMakerRepo->findAll(),
            'form' => $form
        ]);
    }

    private function generateQuery(string $tableName, array $data, string $create_query): string
    {
        $tableName = $this->validateString($tableName);
        $data['columns'] = $this->validateColumns($data['columns']);
        $hasId = true;

        // Add 'id' column if not already present in the $columns array
        if (!in_array('id', $data['columns'])) {
            $hasId = false;
            array_unshift($data['columns'], 'id');
        }

        $query = $this->generateDataQuery($tableName, $data, $hasId);

        if (!empty($create_query) && $create_query == 1) {
            $create_query = $this->generateCreateQuery($tableName, $data['columns']);
            $query = $create_query . PHP_EOL . PHP_EOL . $query;
        }

        return $query;
    }

    private function generateCreateQuery(string $tableName, array $columns): string
    {
        $query = "CREATE TABLE IF NOT EXISTS `$tableName` (" . PHP_EOL;

        foreach ($columns as $column) {
            if ($column == 'id') {
                $query .= " `id` int(11) NOT NULL AUTO_INCREMENT, " . PHP_EOL;
                continue;
            }

            if (str_contains($column, 'date')) {
                $query .= " `$column` DATETIME DEFAULT NULL, " . PHP_EOL;
            } else {
                $query .= " `$column` VARCHAR(255) DEFAULT NULL, " . PHP_EOL;
            }
        }

        $query .= " PRIMARY KEY (`id`)" . PHP_EOL . ");";

        return $query;
    }

    private function generateDataQuery(string $tableName, array $data, bool $hasId): string
    {
        $columns = $data['columns'];
        $rows = $data['rows'];

        $columnNameString = implode(', ', array_map(function ($column) {
            return "`$column`";
        }, $columns));

        $insertQueries = [];
        foreach ($rows as $row) {
            $values = [];

            if (!$hasId) {
                $values[] = "NULL";
            }

            foreach ($row as $value) {
                // Escape each value to prevent SQL injection
                $values[] = "'" . addslashes($value) . "'";
            }
            $insertValues = '(' . implode(', ', $values) . ')';
            $insertQueries[] = "INSERT INTO `$tableName` ($columnNameString) VALUES $insertValues;";
        }

        return implode(PHP_EOL, $insertQueries);
    }

    private function validateColumns(array $columns): array
    {
        $results = [];
        if (!empty($columns)) {
            foreach ($columns as $column) {
                $results[] = $this->validateString($column);
            }
        }

        return $results;
    }

    private function validateString(string $string): string
    {
        $string = strtolower($string);
        return str_replace(" ", '_', $string);
    }

    private function extractCSVData($filePath): array
    {
        $columns = [];
        $rows = [];

        $handle = fopen($filePath, 'r');

        $skip = false;
        while (($data = fgetcsv($handle)) !== false) {
            if (!$skip) {
                $skip = true;
                $columns = $data;
                continue;
            }

            $rows[] = $data;
        }

        fclose($handle);

        return ['columns' => $columns, 'rows' => $rows];
    }

}
