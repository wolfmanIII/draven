<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Policy;
use App\Form\PolicyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/policies', name: 'admin_policy_')]
class PolicyController extends BaseController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $policies = $em->getRepository(Policy::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/policy/index.html.twig', [
            'policies' => $policies,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $policy = new Policy();
        $form = $this->createForm(PolicyType::class, $policy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $policy->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($policy);
            $em->flush();

            return $this->redirectToRoute('admin_policy_index');
        }

        return $this->renderTurbo('admin/policy/form.html.twig', $form, [
            'form' => $form,
            'policy' => $policy,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Policy $policy, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PolicyType::class, $policy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $policy->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            return $this->redirectToRoute('admin_policy_index');
        }

        return $this->renderTurbo('admin/policy/form.html.twig', $form, [
            'form' => $form,
            'policy' => $policy,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Policy $policy, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_policy_'.$policy->getId(), $request->request->get('_token'))) {
            $em->remove($policy);
            $em->flush();
        }

        return $this->redirectToRoute('admin_policy_index');
    }
}
