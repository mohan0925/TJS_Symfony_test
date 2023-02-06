<?php

namespace App\Controller;

use App\Entity\Employees;
use App\Form\EmployeeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmployeesController extends AbstractController
{
    #[Route('/', name: 'app_employees')]
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Employees::class);
        $employeesData = $repository->findAll();

        $employees = new Employees();
        $form = $this->createForm(EmployeeType::class, $employees, [
            'action' => $this->generateUrl('app_employees')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employees);
            $entityManager->flush();

            $this->addFlash('success', 'Data added successfully....!');
            return $this->redirect($request->getUri());
        }
        return $this->render('index.html.twig', array('employees' => $form->createView(), 'employeesData' => $employeesData));
    }
}
