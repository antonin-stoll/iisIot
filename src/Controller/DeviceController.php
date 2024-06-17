<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\KPI;
use App\Entity\Parameter;
use App\Entity\System;
use App\Form\DeviceAssignFormType;
use App\Form\DeviceFormType;
use App\Form\KPIFormType;
use App\Form\ParameterFormType;
use App\Repository\AccountRepository;
use App\Repository\KPIRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\DeviceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DeviceController extends AbstractController
{
    private $deviceRepository;
    private $accountRepository;
    private $kpiRepository;
    private $em;

    public function __construct(DeviceRepository $deviceRepository, AccountRepository $accountRepository,
                                KPIRepository $kpiRepository, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->deviceRepository = $deviceRepository;
        $this->accountRepository = $accountRepository;
        $this->kpiRepository = $kpiRepository;
    }

    #[Route('/device', name: 'app_device')]
    public function index(UserInterface $user): Response
    {
        $devices = $this->deviceRepository->findBy(['owner' => $user]);
        $assignForms = [];

        foreach ($devices as $device) {
            $assignForms[$device->getId()] = $this->createForm(DeviceAssignFormType::class, $device, [
                'action' => $this->generateUrl('device_assign_system', ['id' => $device->getId()]),
                'user' => $user, // Pass the UserInterface object
                'method' => 'POST',
            ])->createView();
        }

        return $this->render('device/index.html.twig', [
            'devices' => $devices,
            'AssignFormType' => $assignForms,
        ]);
    }

    #[Route('/device/create', name: 'create_device')]
    public function create(Request $request, UserInterface $user): Response
    {
        $device = new Device();
        $form = $this->createForm(DeviceFormType::class, $device);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $device->setOwner($this->accountRepository->findOneBy(['username' => $user->getUserIdentifier()]));

            $this->em->persist($device);
            $this->em->flush();

            return $this->redirectToRoute('app_device');
        }

        return $this->render('device/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/device/delete/{id}', name: 'delete_device', methods: ['GET', 'DELETE'])]
    public function delete($id): Response
    {
        $device = $this->deviceRepository->find($id);
        $this->em->remove($device);
        $this->em->flush();

        return  $this->redirectToRoute('app_device');
    }

    #[Route('/device/{id}/add-parameter', name: 'add_parameter_to_device')]
    public function addParameter(Request $request, Device $device): Response
    {
        $parameter = new Parameter();
        $form = $this->createForm(ParameterFormType::class, $parameter);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $parameter = $form->getData();
            $device->addParameter($parameter);

            $this->em->persist($parameter);
            $this->em->flush();

            return $this->redirectToRoute('app_device', ['id' => $device->getId()]);
        }

        return $this->render('device/add_parameter.html.twig', [
            'form' => $form->createView(),
            'device' => $device,
        ]);
    }

    #[Route('/device/{id}/add-kpi', name: 'add_kpi_to_device')]
    public function addKPI(Request $request, Device $device): Response
    {
        $KPI = new KPI();
        $form = $this->createForm(KPIFormType::class, $KPI, ['device' => $device->getId()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $KPI = $form->getData();
            $device->addKpi($KPI);

            $this->em->persist($KPI);
            $this->em->flush();

            return $this->redirectToRoute('app_device', ['id' => $device->getId()]);
        }

        return $this->render('device/add_kpi.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/device/{deviceId}/edit-kpi/{kpiId}', name: 'edit_kpi')]
    public function editKPI(Request $request, $deviceId, $kpiId): Response
    {
        $KPI = $this->kpiRepository->find($kpiId);
        $device = $this->deviceRepository->find($deviceId);
        $form = $this->createForm(KPIFormType::class, $KPI, ['device' => $device->getId()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $KPI = $form->getData();

            $this->em->persist($KPI);
            $this->em->flush();

            return $this->redirectToRoute('app_device');
        }

        return $this->render('device/add_kpi.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/device/{id}/assign', name: 'device_assign_system', methods: ['POST'])]
    public function assignSystemToDevice(Request $request, Device $device, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $form = $this->createForm(DeviceAssignFormType::class, $device, [
            'user' => $user, // Pass the UserInterface object as an option
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $system = $form->get('system')->getData();
            $device->addSystem($system);
            $entityManager->persist($device);
            $entityManager->flush();

            $this->addFlash('success', 'Device assigned to system successfully.');

            return $this->redirectToRoute('app_device');
        }
        /*return $this->render('device/index.html.twig', [
            'AssignFormType' => $form->createView(),
            'device' => $device,
            'user' => $user,
        ]);*/
        return $this->redirectToRoute('app_device');
    }

}
