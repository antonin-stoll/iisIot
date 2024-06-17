<?php

namespace App\Controller;

use App\Entity\ShareRequest;
use App\Repository\AccountRepository;
use App\Repository\ShareRequestRepository;
use App\Repository\SystemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class RequestController extends AbstractController
{
    private $em;
    private $systemRepository;
    private $accountRepository;
    private $requestRepository;

    public function __construct(SystemRepository $systemRepository, AccountRepository $accountRepository,
                                ShareRequestRepository $requestRepository, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->systemRepository = $systemRepository;
        $this->accountRepository = $accountRepository;
        $this->requestRepository = $requestRepository;
    }
    #[Route('/request', name: 'app_request')]
    public function index(UserInterface $user): Response
    {
        $requests = $this->requestRepository->findRequestsByRequestedAccount($user);

        return $this->render('request/index.html.twig', [
            'requests' => $requests,
        ]);
    }

    #[Route('/request/create/{id}', name: 'request_create')]
    public function create($id, UserInterface $user): Response
    {
        $request = new ShareRequest();
        $request->setRequester($user);
        $request->setSystem($this->systemRepository->find($id));
        $this->em->persist($request);
        $this->em->flush();

        return  $this->redirectToRoute('app_index');
    }

    #[Route('/request/accept/{id}', name: 'request_accept')]
    public function acceptRequest($id, UserInterface $user): Response
    {
        $requestedAccount = $this->requestRepository->find($id)->getSystem()->getOwner();
        if ($user === $requestedAccount){
            $request = $this->requestRepository->find($id);
            $system = $request->getSystem();
            $system->addUser($request->getRequester());

            $this->em->remove($request);
            $this->em->flush();
        }
        return  $this->redirectToRoute('app_request');
    }

    #[Route('/request/reject/{id}', name: 'request_reject')]
    public function rejectRequest($id, UserInterface $user): Response
    {
        $requestedAccount = $this->requestRepository->find($id)->getSystem()->getOwner();
        if ($user === $requestedAccount){
            $request = $this->requestRepository->find($id);

            $this->em->remove($request);
            $this->em->flush();
        }
        return  $this->redirectToRoute('app_request');
    }
}
