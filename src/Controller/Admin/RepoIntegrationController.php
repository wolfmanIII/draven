<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\RepoIntegration;
use App\Form\RepoIntegrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/integrations', name: 'admin_integration_')]
class RepoIntegrationController extends BaseController
{
    public const CONTROLLER_NAME = 'RepoIntegrationController';

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $integrations = $em->getRepository(RepoIntegration::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/integration/index.html.twig', [
            'integrations' => $integrations,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $integration = new RepoIntegration();
        $form = $this->createForm(RepoIntegrationType::class, $integration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $integration->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($integration);
            $em->flush();

            return $this->redirectToRoute('admin_integration_index');
        }

        return $this->renderTurbo('admin/integration/form.html.twig', $form, [
            'form' => $form,
            'integration' => $integration,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(RepoIntegration $integration, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RepoIntegrationType::class, $integration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $integration->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            return $this->redirectToRoute('admin_integration_index');
        }

        return $this->renderTurbo('admin/integration/form.html.twig', $form, [
            'form' => $form,
            'integration' => $integration,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(RepoIntegration $integration, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_integration_'.$integration->getId(), $request->request->get('_token'))) {
            $em->remove($integration);
            $em->flush();
        }

        return $this->redirectToRoute('admin_integration_index');
    }
}
