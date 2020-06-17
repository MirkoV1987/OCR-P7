<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

class FormErrors
{
    /**
     * Get form errors and format them
     * @param FormInterface $form
     * @return Array
     */
    public function getErrors(FormInterface $form) : Array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors['error'][] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface && $this->getErrors($childForm)) {
                $childErrors = $this->getErrors($childForm);
                $errors[$childForm->getName()] = $childErrors;
            }
        }
        return $errors;
    }
}