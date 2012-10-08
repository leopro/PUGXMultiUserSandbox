<?php

namespace Acme\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationUserOneFormType extends BaseRegistrationFormType
{
    /**
     * @param FormBuilder $builder
     * @param array       $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fos_user_registration_form';
    }
}