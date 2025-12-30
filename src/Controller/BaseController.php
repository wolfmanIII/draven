<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    public const CONTROLLER_NAME = 'BaseController';

    /**
     * Render con `controller_name` già iniettato nel contesto.
     */
    protected function renderWithController(string $template, array $parameters = [], ?Response $response = null): Response
    {
        $parameters['controller_name'] = static::CONTROLLER_NAME ?? null;

        return parent::render($template, $parameters, $response);
    }

    /**
     * Render con supporto a Turbo:
     * - 200 se form non sottomesso
     * - 422 se form sottomesso ma NON valido (altrimenti Turbo non mostra gli errori)
     *
     * Accetta l'opzione 'form' (FormInterface); qui viene trasformata in FormView e usata per valutare lo stato.
     */
    protected function renderTurbo(string $template, array $options = []): Response
    {
        $form = $options['form'] ?? null;
        if ($form instanceof FormInterface) {
            $options['form'] = $form->createView();
        }

        $options['controller_name'] = static::CONTROLLER_NAME ?? null;
        $response = $this->render($template, $options);

        if ($form instanceof FormInterface && $form->isSubmitted() && !$form->isValid()) {
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }

        return $response;
    }
}
