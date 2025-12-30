<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Environment;
use App\Form\EnvironmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/environments', name: 'admin_environment_')]
class EnvironmentController extends BaseController
{
    public const CONTROLLER_NAME = 'EnvironmentController';

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $environments = $em->getRepository(Environment::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->renderWithController('admin/environment/index.html.twig', [
            'environments' => $environments,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $environment = new Environment();
        $form = $this->createForm(EnvironmentType::class, $environment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $environment->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($environment);
            $em->flush();

            return $this->redirectToRoute('admin_environment_index');
        }

        return $this->renderTurbo('admin/environment/form.html.twig', [
            'form' => $form,
            'environment' => $environment,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Environment $environment, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EnvironmentType::class, $environment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $environment->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            return $this->redirectToRoute('admin_environment_index');
        }

        return $this->renderTurbo('admin/environment/form.html.twig', [
            'form' => $form,
            'environment' => $environment,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Environment $environment, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_environment_'.$environment->getId(), $request->request->get('_token'))) {
            $em->remove($environment);
            $em->flush();
        }

        return $this->redirectToRoute('admin_environment_index');
    }
}
