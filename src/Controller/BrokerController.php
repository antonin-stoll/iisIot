<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Parameter;
use App\Form\ParameterFormType;
use App\Repository\DeviceRepository;
use App\Repository\ParameterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrokerController extends AbstractController
{

    private $deviceRepository;
    private $parameterRepository;
    private $em;

    public function __construct(DeviceRepository $deviceRepository, ParameterRepository $parameterRepository, EntityManagerInterface $em)
    {
        $this->deviceRepository = $deviceRepository;
        $this->parameterRepository = $parameterRepository;
        $this->em = $em;
    }

    #[Route('/broker', name: 'app_broker')]
    public function index(): Response
    {
        $devices = $this->deviceRepository->findAll();
        return $this->render('broker/index.html.twig', [
            'devices' => $devices,
        ]);
    }
    #[Route('/broker/{id}/edit', name: 'edit_parameter_value')]
    public function editParameterValue(Request $request, $id): Response
    {
        $parameter = $this->parameterRepository->find($id);
        $form = $this->createForm(ParameterFormType::class, $parameter, [
            'broker' => true,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $parameter = $form->getData();

            $this->em->persist($parameter);
            $this->em->flush();

            return $this->redirectToRoute('app_broker');
        }

        return $this->render('device/add_parameter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
