<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Event\ProductCreatedEvent;
use App\Event\ProductUpdatedEvent;
use App\Event\ProductDeletedEvent;
use App\Event\EntityCreatedEvent;
use App\Event\EntityUpdatedEvent;
use App\Event\EntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use App\EventListener\ProductCreatedListener;

#[Route('/product')]
class ProductController extends AbstractController
{
    protected $eventDispatcher;
    protected $entityManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
//        $category = new Category();
//        $category->setName('Computer Peripherals');
//
//        $product = new Product();
//        $product->setName('Keyboard');
//        $product->setPrice(19.99);
//
//        // relates this product to the category
//        $product->setCategory($category);
//
//        $this->entityManager->persist($category);
//        $this->entityManager->persist($product);
//        $this->entityManager->flush();

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

        //  Dispatch Event
            $event = new EntityCreatedEvent($product);
            $this->eventDispatcher->dispatch($event, EntityCreatedEvent::NAME);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
//        $categoryName = $product?->getCategory()?->getName();
//        echo $categoryName;

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product): Response
    {
        $oldProduct = clone $product;

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Get dirty dta
            $dirty = $this->entityManager->getUnitOfWork();
            $dirty->computeChangeSets();
            $dirtyData = $dirty->getEntityChangeSet($product);

            $this->entityManager->flush();

            //  Dispatch Event
            $event = new EntityUpdatedEvent($product, $dirtyData);
            $this->eventDispatcher->dispatch($event, EntityUpdatedEvent::NAME);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {

            $event = new EntityDeletedEvent($product);
            $this->eventDispatcher->dispatch($event, EntityDeletedEvent::NAME);

            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
