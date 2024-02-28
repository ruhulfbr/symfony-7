<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

use App\Event\EntityCreatedEvent;
use App\Event\EntityDeletedEvent;
use App\Event\EntityUpdatedEvent;

#[Route('/user')]
class UserController extends AbstractController
{
    protected EventDispatcherInterface $eventDispatcher;
    protected EntityManagerInterface $entityManager;
    protected UserRepository $userRepository;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface   $entityManager,
        UserRepository           $userRepository
    )
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $user->getPassword(); // Assuming you have a method to retrieve the plain text password from the user entity
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);


            // Use the repository to save the user entity
            $this->userRepository->create($user);

            //  Dispatch Event
            $event = new EntityCreatedEvent($user);
            $this->eventDispatcher->dispatch($event, EntityCreatedEvent::NAME);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get dirty dta
            $entityUnitOfWork = $this->entityManager->getUnitOfWork();
            $entityUnitOfWork->computeChangeSets();
            $dirtyData = $entityUnitOfWork->getEntityChangeSet($user);

            // Use the repository to update the user entity
            $this->userRepository->update($user);

            //  Dispatch Event
            $event = new EntityUpdatedEvent($user, $dirtyData);
            $this->eventDispatcher->dispatch($event, EntityUpdatedEvent::NAME);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            //  Dispatch Event
            $event = new EntityDeletedEvent($user);
            $this->eventDispatcher->dispatch($event, EntityDeletedEvent::NAME);

            // Use the repository to delete the user entity
            $this->userRepository->delete($user);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
