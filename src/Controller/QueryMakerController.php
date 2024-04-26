<?php

namespace App\Controller;

use App\Entity\QueryMakerCSV;
use App\Form\QueryMakerType;
use Doctrine\ORM\EntityManagerInterface;
use Ruhulfbr\QueryGeneratorFromCsv\QueryGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class QueryMakerController extends AbstractController
{
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
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

            // Generate Query
            $generator = new QueryGenerator($filePath, $create_query, $tableName);
            $queryResult = $generator->generate();

            // Delete Uploaded File after generate
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return $this->render('query_maker/query.html.twig', [
                'message' => $queryResult->message,
                'table_name' => str_replace(' ', '_', $tableName),
                'query_data' => $queryResult->type == 'success' ? $queryResult->query : ''
            ]);
        }

        return $this->render('query_maker/index.html.twig', [
            'form' => $form
        ]);
    }

}
