<?php

namespace App\Controller;

use App\Form\AccountFormType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    private $em;
    private AccountRepository $accountRepository;
    private $userPasswordHasher;

    public function __construct(AccountRepository $accountRepository, EntityManagerInterface $em,
                                UserPasswordHasherInterface $PasswordHasher)
    {
        $this->em = $em;
        $this->accountRepository = $accountRepository;
        $this->userPasswordHasher = $PasswordHasher;
    }

    #[Route('/administration', name: 'app_administration')]
    public function index(): Response
    {
        $users = $this->accountRepository->findAll();
        return $this->render('administration/index.html.twig', [
            'users' => $users,
            'id' => 55555,
        ]);

    }

    #[Route('/administration/userEdit/{id}', name: 'administration_userEdit')]
    public function editUser(Request $request, int $id): Response
    {
        $user = $this->accountRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(AccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $plainPassword = $form->get('plainPassword')->getData();
            if (!empty($plainPassword)){
                $this->accountRepository->upgradePassword($user,
                    $this->userPasswordHasher->hashPassword($user, $plainPassword)
                );
            }

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('app_administration');
        }

        return $this->render('administration/userEdit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/administration/userDelete/{id}', name: 'administration_userDelete')]
    public function deleteUser(Request $request, int $id): Response
    {
        $user = $this->accountRepository->find($id);

        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('app_administration');
    }
}
