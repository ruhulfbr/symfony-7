<?php

namespace App\Controller;

use App\Entity\QueryMakerCSV;
use App\Form\QueryMakerType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ruhul\CSVQuery\Exceptions\ColumnNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ruhulfbr\CsvToQuery\Query;
use Ruhul\CSVQuery\CSVQ as csvq;

class QueryMakerController extends AbstractController
{
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Use csvqry Package
        // $this->queryOnCsv();

        $queryMaker = new QueryMakerCSV();
        $form = $this->createForm(QueryMakerType::class, $queryMaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableName = $form->get('table_name')->getData();
            $create_query = $form->get('create_query')->getData() ?? false;
            $csvFile = $form->get('csvFile')->getData();

            // Upload CSV File
            $fileDir = $this->getParameter('kernel.project_dir') . '/public/csv/';
            $fileName = $csvFile->getClientOriginalName();
            $csvFile->move($fileDir, $fileName);
            $filePath = $fileDir . $fileName;

            $data = $this->queryOnCsv($filePath);

            // Generate Query
            $generator = new Query($filePath, $create_query, $tableName);
            $queryResult = $generator->generate();

            // Delete Uploaded File after generate
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            pr($data);

            return $this->render('query_maker/query.html.twig', [
                'message' => $queryResult->message,
                'table_name' => str_replace(' ', '_', $tableName),
                'query_data' => $queryResult->type == 'success' ? $queryResult->query : '',
                'data' => $data
            ]);
        }

        return $this->render('query_maker/index.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @throws ColumnNotFoundException
     * @throws Exception
     */
    private function queryOnCsv(string $filePath = null): mixed
    {
        if ($filePath === null) {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/csv/sample-csv-100k.csv';
        }

        return csvq::from($filePath)->where('Data_value', '<', 1250)->min('Data_value');
    }

    /**
     * @throws ColumnNotFoundException
     * @throws Exception
     */
    private function queryOnCsv2(string $filePath = null): void
    {
        $startTime = microtime(true);
        $startMemory = memory_get_peak_usage(true);

        if ($filePath === null) {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/csv/sample-csv-100k.csv';
        }

        $data = csvq::from($filePath)->limit(10)->get();

        $endTime = microtime(true);
        $endMemory = memory_get_peak_usage(true);

        $executionTime = round($endTime - $startTime, 4);
        $memoryUsage = round(($endMemory - $startMemory) / (1024 * 1024), 3);

        echo "Execution time: " . $executionTime . ' seconds <br/>';
        echo 'Memory usage: ' . $memoryUsage . ' MB' . PHP_EOL;

        echo "<pre>";
        print_r($data);
        echo "</pre>";
        // exit();


        return;
    }

}
