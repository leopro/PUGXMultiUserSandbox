<?php

namespace Acme\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationUserOneController extends BaseController
{
    public function registerAction()
    {
        $handler = $this->container->get('pugx_multi_user.controller.handler');
        $discriminator = $this->container->get('pugx_user_discriminator');
        
        $return = $handler->registration('Acme\UserBundle\Entity\UserOne');
        $form = $discriminator->getRegistrationForm();

        if ($return instanceof RedirectResponse) {
            return $return;
        }

        return $this->container->get('templating')->renderResponse('AcmeUserBundle:Registration:user_one.form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}