<?php

namespace Acme\UserBundle\Controller;

use PUGX\MultiUserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationUserTwoController extends BaseController
{
    public function registerAction()
    {
        $discriminator = $this->container->get('pugx_user_discriminator');
        $discriminator->setClass('Acme\UserBundle\Entity\UserTwo');

        $form = $discriminator->getRegistrationForm();

        $return = parent::registerAction();

        if ($return instanceof RedirectResponse) {
            return $return;
        }
        
        return $this->container->get('templating')->renderResponse('AcmeUserBundle:Registration:user_two.form.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));

    }
}