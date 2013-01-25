<?php

namespace Acme\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

class RegistrationUserThreeController extends BaseController
{
    public function registerAction()
    {
        return $this->container
                    ->get('pugx_multi_user.registration_manager')
                    ->register('Acme\UserBundle\Entity\UserThree');
    }
}